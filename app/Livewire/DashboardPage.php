<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\LogAktivitas;
use App\Models\LogInvalid;
use App\Models\EspDevice;
use Carbon\Carbon;

class DashboardPage extends Component
{
    public int $chartDays = 7; // sinkron dengan dropdown

    public function setChartDays($days)
    {
        $this->chartDays = (int) $days ?: 7;
        $this->dispatch('reload-chart', days: $this->chartDays);
    }

    public function render()
    {
        $nowWita = Carbon::now('Asia/Makassar');
        $today   = $nowWita->toDateString();

        // === Pengguna Terdaftar (Mahasiswa + Dosen) ===
        $penggunaTerdaftar = (int) Mahasiswa::count() + (int) Dosen::count();

        // === Angka harian (log valid) ===
        $jumlahMasuk  = LogAktivitas::whereDate('tanggal', $today)->whereNotNull('waktu_masuk')->count();
        $jumlahKeluar = LogAktivitas::whereDate('tanggal', $today)->whereNotNull('waktu_keluar')->count();

        // === Invalid harian ===
        $aktivitasInvalid = LogInvalid::whereDate('waktu', $today)->count();

        // === Aktivitas Terbaru (ambil nama dari mahasiswa ATAU dosen) ===
        $aktivitasTerbaru = LogAktivitas::with(['mahasiswa','dosen'])
            ->whereDate('tanggal', $today)
            ->orderByRaw('GREATEST(
                IFNULL(TIME(waktu_masuk), "00:00:00"),
                IFNULL(TIME(waktu_keluar), "00:00:00")
            ) DESC')
            ->limit(5)
            ->get()
            ->map(function ($log) {
                // Nama prioritas: mahasiswa -> dosen -> fallback
                $nama = $log->mahasiswa->nama ?? $log->dosen->nama ?? 'Tidak diketahui';

                $fmtMasuk  = $log->waktu_masuk
                    ? Carbon::parse($log->waktu_masuk)->timezone('Asia/Makassar')->format('H:i') . ' WITA'
                    : null;
                $fmtKeluar = $log->waktu_keluar
                    ? Carbon::parse($log->waktu_keluar)->timezone('Asia/Makassar')->format('H:i') . ' WITA'
                    : null;

                if ($log->waktu_masuk && (!$log->waktu_keluar || $log->waktu_masuk > $log->waktu_keluar)) {
                    return (object)[
                        'nama'    => $nama,
                        'jenis'   => 'masuk',
                        'waktu'   => $fmtMasuk,
                        'ruangan' => $log->ruangan ?? null
                    ];
                } elseif ($log->waktu_keluar) {
                    return (object)[
                        'nama'    => $nama,
                        'jenis'   => 'keluar',
                        'waktu'   => $fmtKeluar,
                        'ruangan' => $log->ruangan ?? null
                    ];
                }
                return null;
            })
            ->filter()
            ->values();

        // === Status ESP ===
        $kelas = ['SmartClass 1', 'SmartClass 2'];
        $statusEspKelas = [];
        foreach ($kelas as $namaKelas) {
            $device   = EspDevice::where('nama_kelas', $namaKelas)->first();
            $status   = 'Tidak Aktif';
            $lastSeen = 'Belum Pernah';

            if ($device && $device->last_seen) {
                $lastSeenCarbon = Carbon::parse($device->last_seen)->timezone('Asia/Makassar');
                $lastSeen = $lastSeenCarbon->format('d M Y, H:i:s') . ' WITA';
                if ($lastSeenCarbon->diffInMinutes(Carbon::now('Asia/Makassar')) <= 2) {
                    $status = 'Aktif';
                }
            }

            $statusEspKelas[] = [
                'nama_kelas' => $namaKelas,
                'status'     => $status,
                'last_seen'  => $lastSeen,
            ];
        }

        return view('livewire.dashboard-page', compact(
            'penggunaTerdaftar',
            'jumlahMasuk',
            'jumlahKeluar',
            'aktivitasInvalid',
            'statusEspKelas',
            'aktivitasTerbaru'
        ));
    }
}
