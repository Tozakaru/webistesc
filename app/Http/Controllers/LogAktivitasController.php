<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\LogAktivitas;
use App\Models\LogInvalid;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class LogAktivitasController extends Controller
{
    public function scanRuangan1(Request $request)
    {
        return $this->scanByRuangan($request, 'ruangan1');
    }

    public function scanRuangan2(Request $request)
    {
        return $this->scanByRuangan($request, 'ruangan2');
    }

    public function scanByRuangan(Request $request, $ruangan)
    {
        $uid    = $request->uid_rfid;
        $reader = strtolower($request->reader ?? '');
    
        $mahasiswa = Mahasiswa::where('uid_rfid', $uid)->first();
        if (!$mahasiswa) {
            LogInvalid::create([
                'uid_rfid' => $uid,
                'reader'   => $reader,
                'waktu'    => now(),
                'ruangan'  => $ruangan,
            ]);
            return response()->json([
                'authorized' => false,
                'message'    => 'UID tidak dikenal',
            ]);
        }
    
        $today = today();
    
        if ($reader === 'masuk') {
            // SELALU TERIMA: buat baris baru meskipun masih ada sesi terbuka
            LogAktivitas::create([
                'mahasiswa_id' => $mahasiswa->id,
                'tanggal'      => $today,
                'waktu_masuk'  => now(),
                'ruangan'      => $ruangan,
            ]);
    
            $sesiKe = LogAktivitas::where('mahasiswa_id', $mahasiswa->id)
                ->where('ruangan', $ruangan)
                ->whereDate('tanggal', $today)
                ->count();
    
            return response()->json([
                'authorized' => true,
                'message'    => 'Waktu masuk tercatat',
                'status'     => 'masuk',
                'sesi_ke'    => $sesiKe,
            ]);
        }
    
        if ($reader === 'keluar') {
            // Kalau ada sesi terbuka (masuk!=null & keluar=null), pasangkan.
            $openLog = LogAktivitas::where('mahasiswa_id', $mahasiswa->id)
                ->where('ruangan', $ruangan)
                ->whereDate('tanggal', $today)
                ->whereNotNull('waktu_masuk')
                ->whereNull('waktu_keluar')
                ->latest('waktu_masuk')
                ->first();
    
            if ($openLog) {
                $openLog->update(['waktu_keluar' => now()]);
                return response()->json([
                    'authorized' => true,
                    'message'    => 'Waktu keluar tercatat (dipasangkan dengan sesi terakhir)',
                    'status'     => 'keluar',
                    'paired'     => true,
                ]);
            }
    
            // TIDAK ADA sesi terbuka → tetap terima, catat keluar-saja
            LogAktivitas::create([
                'mahasiswa_id' => $mahasiswa->id,
                'tanggal'      => $today,
                'waktu_keluar' => now(),
                'ruangan'      => $ruangan,
            ]);
    
            return response()->json([
                'authorized' => true,
                'message'    => 'Waktu keluar tercatat (tanpa sesi masuk)',
                'status'     => 'keluar',
                'paired'     => false,
            ]);
        }
    
        return response()->json([
            'authorized' => false,
            'message'    => 'Jenis reader tidak dikenali',
        ]);
    }
      

    public function aktivitasInvalid()
    {
        return view('pages.logaktivitas.invalid');
    }
    

    public function ruangan1(Request $request)
    {
        $filter = $request->filter;
        $today = Carbon::today();

        $query = LogAktivitas::with('mahasiswa')
                    ->where('ruangan', 'ruangan1')
                    ->whereDate('tanggal', $today);

        if ($filter == 'masuk') {
            $query->whereNotNull('waktu_masuk');
        } elseif ($filter == 'keluar') {
            $query->whereNotNull('waktu_keluar');
        }

        $logs = $query
        ->orderByDesc(DB::raw('COALESCE(waktu_keluar, waktu_masuk)'))
        ->paginate(5);

        return view('pages.logaktivitas.ruangan1', compact('logs'));
    }

    public function ruangan2(Request $request)
    {
        $filter = $request->filter;
        $today = Carbon::today();

        $query = LogAktivitas::with('mahasiswa')
                    ->where('ruangan', 'ruangan2')
                    ->whereDate('tanggal', $today);

        if ($filter == 'masuk') {
            $query->whereNotNull('waktu_masuk');
        } elseif ($filter == 'keluar') {
            $query->whereNotNull('waktu_keluar');
        }

        $logs = $query
        ->orderByDesc(DB::raw('COALESCE(waktu_keluar, waktu_masuk)'))
        ->paginate(5);

        return view('pages.logaktivitas.ruangan2', compact('logs'));
    }

    public function rekapan(Request $request)
    {
        $start = $request->input('start_date');
        $end   = $request->input('end_date');
        $q     = trim($request->input('q', ''));
    
        // tampilkan tabel hanya jika dua tanggal diisi
        $showTable = $start && $end;
    
        $logs = collect(); // default kosong (biar tidak error di view)
        if ($showTable) {
            // validasi ringan
            $request->validate([
                'start_date' => ['required','date'],
                'end_date'   => ['required','date','after_or_equal:start_date'],
                'q'          => ['nullable','string','max:100'],
            ]);
    
            $logs = LogAktivitas::with('mahasiswa')
                ->whereBetween('tanggal', [$start, $end])
                ->when($q, function ($query) use ($q) {
                    $query->whereHas('mahasiswa', function ($sub) use ($q) {
                        $sub->where('nama', 'like', '%'.$q.'%')
                            ->orWhere('nim', 'like', '%'.$q.'%');
                    });
                })
                ->orderBy('tanggal')
                ->paginate(25)
                ->withQueryString();
        }
        
        return view('pages.logaktivitas.rekapan', [
            'logs' => $logs,
            'showTable' => $showTable,
        ]);
    }

    public function exportCsv(Request $request)
    {
        $bulan        = $request->bulan; // bisa kosong
        $tahun        = (int) $request->tahun;
        $minggu       = $request->minggu ? (int) $request->minggu : null;
        $mahasiswaId  = $request->mahasiswa_id;
        $startParam   = $request->start_date;  // range mingguan akurat (opsi baru)
        $endParam     = $request->end_date;

        // periode
        if ($startParam && $endParam) {
            $periodStart = Carbon::parse($startParam)->startOfDay();
            $periodEnd   = Carbon::parse($endParam)->endOfDay();
        } else {
            if ($bulan) {
                $periodStart = Carbon::createFromDate($tahun, (int)$bulan, 1)->startOfMonth();
                $periodEnd   = (clone $periodStart)->endOfMonth();
            } else {
                $periodStart = Carbon::createFromDate($tahun, 1, 1)->startOfYear();
                $periodEnd   = Carbon::createFromDate($tahun, 12, 31)->endOfYear();
            }
        }

        $logsQuery = LogAktivitas::with('mahasiswa')
            ->whereBetween('tanggal', [$periodStart->toDateString(), $periodEnd->toDateString()]);
        if ($mahasiswaId) $logsQuery->where('mahasiswa_id', $mahasiswaId);

        $logs = $logsQuery->orderBy('tanggal')->get();

        // kompat lama: jika minggu ada tapi tidak ada start/end dan mode bulanan
        if ($minggu && !($startParam && $endParam) && $bulan) {
            $logs = $logs->filter(fn($log) => Carbon::parse($log->tanggal)->weekOfMonth == $minggu);
        }

        // suffix nama file jika difilter per orang
        $namaSuffix = '';
        if ($mahasiswaId) {
            $mhs = Mahasiswa::find($mahasiswaId);
            if ($mhs) {
                $namaSuffix = '_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $mhs->nim ?: $mhs->nama);
            }
        }

        $filename = 'log_aktivitas'
            . ($startParam ? '_mingguan' : ($bulan ? '_bulanan' : '_tahunan'))
            . $namaSuffix
            . '_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($logs) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 untuk Excel
            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            // Header kolom
            fputcsv($handle, ['Nama', 'NIM', 'Tanggal', 'Waktu Masuk', 'Waktu Keluar'], ';');

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->mahasiswa->nama,
                    $log->mahasiswa->nim,
                    Carbon::parse($log->tanggal)->format('d/m/Y'),
                    $log->waktu_masuk ?? '-',
                    $log->waktu_keluar ?? '-',
                ], ';');
            }

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $bulan        = $request->bulan; // bisa kosong
        $tahun        = (int) $request->tahun;
        $minggu       = $request->minggu ? (int) $request->minggu : null;
        $mahasiswaId  = $request->mahasiswa_id;
        $startParam   = $request->start_date; // range mingguan akurat
        $endParam     = $request->end_date;

        // periode
        if ($startParam && $endParam) {
            $periodStart = Carbon::parse($startParam)->startOfDay();
            $periodEnd   = Carbon::parse($endParam)->endOfDay();
        } else {
            if ($bulan) {
                $periodStart = Carbon::createFromDate($tahun, (int)$bulan, 1)->startOfMonth();
                $periodEnd   = (clone $periodStart)->endOfMonth();
            } else {
                $periodStart = Carbon::createFromDate($tahun, 1, 1)->startOfYear();
                $periodEnd   = Carbon::createFromDate($tahun, 12, 31)->endOfYear();
            }
        }

        $logsQuery = LogAktivitas::with('mahasiswa')
            ->whereBetween('tanggal', [$periodStart->toDateString(), $periodEnd->toDateString()]);
        if ($mahasiswaId) $logsQuery->where('mahasiswa_id', $mahasiswaId);

        $logs = $logsQuery->orderBy('tanggal')->get();

        // kompat lama: jika minggu ada tapi tidak ada start/end dan mode bulanan
        if ($minggu && !($startParam && $endParam) && $bulan) {
            $logs = $logs->filter(fn($log) => Carbon::parse($log->tanggal)->weekOfMonth == $minggu);
        }

        // meta header
        $meta = [
            'formulir'    => 'FORM-QA-LOG-AKT',
            'kode'        => 'FM-198 sd.A rev:0',
            'issue'       => 'A',
            'tgl_efektif' => '26-02-2020',
            'update'      => '0',
            'updated_at'  => '00-00-0000',
        ];

        // judul periode
        if ($startParam && $endParam) {
            $judulPeriode = Carbon::parse($startParam)->format('d M Y')
                          . ' – '
                          . Carbon::parse($endParam)->format('d M Y');
        } elseif ($bulan) {
            $namaBulan = Carbon::create()->month($bulan)->locale('id')->translatedFormat('F');
            $judulPeriode = "$namaBulan $tahun";
        } else {
            $judulPeriode = "Januari–Desember $tahun";
        }

        // tambah nama jika per orang
        $mhs = null;
        if ($mahasiswaId) {
            $mhs = Mahasiswa::find($mahasiswaId);
            if ($mhs) {
                $judulPeriode .= " — {$mhs->nama} ({$mhs->nim})";
            }
        }

        // path logo (pastikan file ada)
        $logoPath = public_path('template/img/logo polimdo2.png');

        $pdf = Pdf::loadView('pages.logaktivitas.pdf', [
                    'logs'         => $logs,
                    'judulPeriode' => $judulPeriode,
                    'meta'         => $meta,
                    'logoPath'     => $logoPath,
                ])
                ->setPaper('A4', 'portrait');

        $namaSuffix = '';
        if ($mhs) {
            $namaSuffix = '_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $mhs->nim ?: $mhs->nama);
        }

        $namaFile = 'log_aktivitas'
            . ($startParam ? '_mingguan' : ($bulan ? '_bulanan' : '_tahunan'))
            . $namaSuffix . '.pdf';

        return $pdf->download($namaFile);
    }
}
