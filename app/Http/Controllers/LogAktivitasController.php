<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\LogAktivitas;
use App\Models\LogInvalid;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class LogAktivitasController extends Controller
{
    /* ==========================
     * SCAN ENDPOINTS (ESP32)
     * ========================== */

    public function scanRuangan1(Request $request)
    {
        return $this->scanByRuangan($request, 'ruangan1');
    }

    public function scanRuangan2(Request $request)
    {
        return $this->scanByRuangan($request, 'ruangan2');
    }

    /**
     * Terima scan RFID + reader ("masuk"/"keluar") dan catat untuk Mahasiswa atau Dosen.
     * - Jika UID milik Mahasiswa => simpan di kolom mahasiswa_id
     * - Jika UID milik Dosen     => simpan di kolom dosen_id
     * - Jika tidak dikenal       => catat ke LogInvalid
     */
    public function scanByRuangan(Request $request, $ruangan)
    {
        $uid    = $request->uid_rfid;
        $reader = strtolower($request->reader ?? ''); // expected: 'masuk' | 'keluar'
        $today  = today();

        /* ===== 1) Cek MAHASISWA lebih dulu ===== */
        $mahasiswa = Mahasiswa::where('uid_rfid', $uid)->first();
        if ($mahasiswa) {
            if ($reader === 'masuk') {
                // Selalu terima masuk (multi-sesi diperbolehkan)
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
                    'role'       => 'mahasiswa',
                    'nama'       => $mahasiswa->nama,
                    'sesi_ke'    => $sesiKe,
                ]);
            }

            if ($reader === 'keluar') {
                // Jika ada sesi terbuka di hari & ruangan yang sama, pasangkan keluar
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
                        'message'    => 'Waktu keluar tercatat (dipasangkan)',
                        'status'     => 'keluar',
                        'role'       => 'mahasiswa',
                        'nama'       => $mahasiswa->nama,
                        'paired'     => true,
                    ]);
                }

                // Tidak ada sesi terbuka → tetap catat keluar-saja
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
                    'role'       => 'mahasiswa',
                    'nama'       => $mahasiswa->nama,
                    'paired'     => false,
                ]);
            }

            return response()->json([
                'authorized' => false,
                'message'    => 'Jenis reader tidak dikenali',
            ], 422);
        }

        /* ===== 2) Jika bukan mahasiswa, cek DOSEN ===== */
        $dosen = Dosen::where('uid_rfid', $uid)->first();
        if ($dosen) {
            if ($reader === 'masuk') {
                LogAktivitas::create([
                    'dosen_id'    => $dosen->id,
                    'tanggal'     => $today,
                    'waktu_masuk' => now(),
                    'ruangan'     => $ruangan,
                ]);

                $sesiKe = LogAktivitas::where('dosen_id', $dosen->id)
                            ->where('ruangan', $ruangan)
                            ->whereDate('tanggal', $today)
                            ->count();

                return response()->json([
                    'authorized' => true,
                    'message'    => 'Waktu masuk tercatat',
                    'status'     => 'masuk',
                    'role'       => 'dosen',
                    'nama'       => $dosen->nama,
                    'sesi_ke'    => $sesiKe,
                ]);
            }

            if ($reader === 'keluar') {
                $openLog = LogAktivitas::where('dosen_id', $dosen->id)
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
                        'message'    => 'Waktu keluar tercatat (dipasangkan)',
                        'status'     => 'keluar',
                        'role'       => 'dosen',
                        'nama'       => $dosen->nama,
                        'paired'     => true,
                    ]);
                }

                LogAktivitas::create([
                    'dosen_id'    => $dosen->id,
                    'tanggal'     => $today,
                    'waktu_keluar'=> now(),
                    'ruangan'     => $ruangan,
                ]);

                return response()->json([
                    'authorized' => true,
                    'message'    => 'Waktu keluar tercatat (tanpa sesi masuk)',
                    'status'     => 'keluar',
                    'role'       => 'dosen',
                    'nama'       => $dosen->nama,
                    'paired'     => false,
                ]);
            }

            return response()->json([
                'authorized' => false,
                'message'    => 'Jenis reader tidak dikenali',
            ], 422);
        }

        /* ===== 3) Bukan Mahasiswa & bukan Dosen => INVALID ===== */
        LogInvalid::create([
            'uid_rfid' => $uid,
            'reader'   => $reader,
            'waktu'    => now(),
            'ruangan'  => $ruangan,
        ]);

        return response()->json([
            'authorized' => false,
            'message'    => 'UID tidak dikenal',
        ], 404);
    }

    /* ==========================
     * HALAMAN & REKAP
     * ========================== */

    public function aktivitasInvalid()
    {
        return view('pages.logaktivitas.invalid');
    }

    /**
     * Versi list log harian untuk ruangan1.
     * Catatan: Kalau halaman kamu sekarang pakai Livewire "log-ruangan",
     * method ini bisa tetap dipakai untuk halaman non-Livewire.
     */
    public function ruangan1(Request $request)
    {
        $filter = $request->filter; // 'masuk' | 'keluar' | null
        $today  = Carbon::today();

        $query = LogAktivitas::with(['mahasiswa','dosen'])
                    ->where('ruangan', 'ruangan1')
                    ->whereDate('tanggal', $today);

        if ($filter === 'masuk')  $query->whereNotNull('waktu_masuk');
        if ($filter === 'keluar') $query->whereNotNull('waktu_keluar');

        $logs = $query
            ->orderByDesc(DB::raw('COALESCE(waktu_keluar, waktu_masuk)'))
            ->paginate(5);

        return view('pages.logaktivitas.ruangan1', compact('logs'));
    }

    /**
     * Versi list log harian untuk ruangan2.
     */
    public function ruangan2(Request $request)
    {
        $filter = $request->filter;
        $today  = Carbon::today();

        $query = LogAktivitas::with(['mahasiswa','dosen'])
                    ->where('ruangan', 'ruangan2')
                    ->whereDate('tanggal', $today);

        if ($filter === 'masuk')  $query->whereNotNull('waktu_masuk');
        if ($filter === 'keluar') $query->whereNotNull('waktu_keluar');

        $logs = $query
            ->orderByDesc(DB::raw('COALESCE(waktu_keluar, waktu_masuk)'))
            ->paginate(5);

        return view('pages.logaktivitas.ruangan2', compact('logs'));
    }

    public function rekapan(Request $request)
    {
        $start   = $request->input('start_date');
        $end     = $request->input('end_date');
        $role    = $request->input('role', 'all'); // 'all' | 'mahasiswa' | 'dosen'
        $person  = $request->input('person');      // 'm:ID' | 'd:ID' | null
    
        $mhsList = Mahasiswa::orderBy('nama')->get(['id','nama','nim']);
        $dsnList = Dosen::orderBy('nama')->get(['id','nama','nip']);
    
        $showTable = $start && $end;
        $logs = collect();
    
        if ($showTable) {
            $request->validate([
                'start_date' => ['required','date'],
                'end_date'   => ['required','date','after_or_equal:start_date'],
                'role'       => ['nullable','in:all,mahasiswa,dosen'],
                'person'     => ['nullable','regex:/^(m:\d+|d:\d+)$/'],
            ]);
    
            $logs = LogAktivitas::with(['mahasiswa','dosen'])
                ->whereBetween('tanggal', [$start, $end])
                ->when($role === 'mahasiswa', fn($q) => $q->whereNotNull('mahasiswa_id'))
                ->when($role === 'dosen',     fn($q) => $q->whereNotNull('dosen_id'))
                ->when($person, function ($query) use ($person) {
                    [$t,$id] = explode(':', $person);
                    if ($t === 'm') $query->where('mahasiswa_id', (int)$id);
                    if ($t === 'd') $query->where('dosen_id', (int)$id);
                })
                ->orderBy('tanggal')
                ->paginate(25)
                ->withQueryString();
        }
    
        return view('pages.logaktivitas.rekapan', compact('logs','showTable','mhsList','dsnList'));
    }
    
    public function exportCsv(Request $request)
    {
        $bulan      = $request->bulan;
        $tahun      = (int) $request->tahun;
        $minggu     = $request->minggu ? (int) $request->minggu : null;
    
        $startParam = $request->start_date;
        $endParam   = $request->end_date;
        $role       = $request->input('role','all');
        $person     = $request->input('person');
    
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
    
        $logsQuery = LogAktivitas::with(['mahasiswa','dosen'])
            ->whereBetween('tanggal', [$periodStart->toDateString(), $periodEnd->toDateString()])
            ->when($role === 'mahasiswa', fn($q) => $q->whereNotNull('mahasiswa_id'))
            ->when($role === 'dosen',     fn($q) => $q->whereNotNull('dosen_id'))
            ->when($person, function ($query) use ($person) {
                if (preg_match('/^(m|d):(\d+)$/', $person, $m)) {
                    if ($m[1] === 'm') $query->where('mahasiswa_id', (int)$m[2]);
                    if ($m[1] === 'd') $query->where('dosen_id',     (int)$m[2]);
                }
            });
    
        $logs = $logsQuery->orderBy('tanggal')->get();
    
        if ($minggu && !($startParam && $endParam) && $bulan) {
            $logs = $logs->filter(fn($log) => Carbon::parse($log->tanggal)->weekOfMonth == $minggu);
        }
    
        $filename = 'log_aktivitas'
                  . ($startParam ? '_mingguan' : ($bulan ? '_bulanan' : '_tahunan'))
                  . '_' . now()->format('Ymd_His') . '.csv';
    
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
    
        $callback = function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['Role','Nama','NIM/NIP','Tanggal','Waktu Masuk','Waktu Keluar'], ';');
    
            foreach ($logs as $log) {
                $isMhs = !is_null($log->mahasiswa_id);
                $role  = $isMhs ? 'Mahasiswa' : 'Dosen';
                $nama  = $isMhs ? ($log->mahasiswa->nama ?? '-') : ($log->dosen->nama ?? '-');
                $idno  = $isMhs ? ($log->mahasiswa->nim  ?? '-') : ($log->dosen->nip   ?? '-');
    
                fputcsv($handle, [
                    $role, $nama, $idno,
                    Carbon::parse($log->tanggal)->format('d/m/Y'),
                    $log->waktu_masuk ?? '-', $log->waktu_keluar ?? '-',
                ], ';');
            }
            fclose($handle);
        };
    
        return Response::stream($callback, 200, $headers);
    }
    
    public function exportPdf(Request $request)
    {
        $bulan      = $request->bulan;
        $tahun      = (int) $request->tahun;
        $minggu     = $request->minggu ? (int) $request->minggu : null;
    
        $startParam = $request->start_date;
        $endParam   = $request->end_date;
        $role       = $request->input('role','all');
        $person     = $request->input('person');
    
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
    
        $logsQuery = LogAktivitas::with(['mahasiswa','dosen'])
            ->whereBetween('tanggal', [$periodStart->toDateString(), $periodEnd->toDateString()])
            ->when($role === 'mahasiswa', fn($q) => $q->whereNotNull('mahasiswa_id'))
            ->when($role === 'dosen',     fn($q) => $q->whereNotNull('dosen_id'))
            ->when($person, function ($query) use ($person) {
                if (preg_match('/^(m|d):(\d+)$/', $person, $m)) {
                    if ($m[1] === 'm') $query->where('mahasiswa_id', (int)$m[2]);
                    if ($m[1] === 'd') $query->where('dosen_id',     (int)$m[2]);
                }
            });
    
        $logs = $logsQuery->orderBy('tanggal')->get();
    
        if ($minggu && !($startParam && $endParam) && $bulan) {
            $logs = $logs->filter(fn($log) => Carbon::parse($log->tanggal)->weekOfMonth == $minggu);
        }
    
        $meta = [
            'formulir'    => 'FORM-QA-LOG-AKT',
            'kode'        => 'FM-198 sd.A rev:0',
            'issue'       => 'A',
            'tgl_efektif' => '26-02-2020',
            'update'      => '0',
            'updated_at'  => '00-00-0000',
        ];
    
        if ($startParam && $endParam) {
            $judulPeriode = Carbon::parse($startParam)->format('d M Y') . ' – ' . Carbon::parse($endParam)->format('d M Y');
        } elseif ($bulan) {
            $namaBulan = Carbon::create()->month($bulan)->locale('id')->translatedFormat('F');
            $judulPeriode = "$namaBulan $tahun";
        } else {
            $judulPeriode = "Januari–Desember $tahun";
        }
    
        if ($role !== 'all') $judulPeriode .= ' — ' . ucfirst($role);
        if ($person && preg_match('/^(m|d):(\d+)$/', $person, $m)) {
            if ($m[1] === 'm') { $mm = Mahasiswa::find((int)$m[2]); if ($mm) $judulPeriode .= " — {$mm->nama} ({$mm->nim})"; }
            else { $dd = Dosen::find((int)$m[2]); if ($dd) $judulPeriode .= " — {$dd->nama} ({$dd->nip})"; }
        }
    
        $logoPath = public_path('template/img/logo polimdo2.png');
    
        $pdf = Pdf::loadView('pages.logaktivitas.pdf', [
                    'logs'         => $logs,
                    'judulPeriode' => $judulPeriode,
                    'meta'         => $meta,
                    'logoPath'     => $logoPath,
                ])->setPaper('A4','portrait');
    
        $namaFile = 'log_aktivitas'
                  . ($startParam ? '_mingguan' : ($bulan ? '_bulanan' : '_tahunan'))
                  . '.pdf';
    
        return $pdf->download($namaFile);
    }
     
}
