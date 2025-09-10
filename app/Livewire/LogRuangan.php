<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LogAktivitas;
use Carbon\Carbon;

class LogRuangan extends Component
{
    use WithPagination;

    public string $ruangan = 'ruangan1';
    public ?string $filter = null;         // 'masuk' | 'keluar' | null (= gabungan)
    public int $perPage = 5;
    public string $tz = 'Asia/Makassar';

    protected $queryString = ['filter'];

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function updatedRuangan()
    {
        $this->resetPage();
    }

    public function setFilter(?string $f = null)
    {
        $this->filter = $f;
    }

    public function render()
    {
        $todayLocal = Carbon::now($this->tz)->toDateString(); // 'YYYY-MM-DD'

        $query = LogAktivitas::with('mahasiswa')
            ->where('ruangan', $this->ruangan)
            ->where(function ($q) use ($todayLocal) {
                $q->whereDate('tanggal', $todayLocal)
                  ->orWhereDate('waktu_masuk', $todayLocal)
                  ->orWhereDate('waktu_keluar', $todayLocal);
            });

        if ($this->filter === 'masuk') {
            $query->whereNotNull('waktu_masuk')
                  ->orderByDesc('waktu_masuk');
        } elseif ($this->filter === 'keluar') {
            $query->whereNotNull('waktu_keluar')
                  ->orderByDesc('waktu_keluar');
        } else {
            // Gabungan: event terbaru = waktu_keluar jika ada, kalau tidak waktu_masuk
            $query->orderByRaw('COALESCE(waktu_keluar, waktu_masuk) DESC')
                  ->orderByDesc('id'); // tie-breaker
        }

        $logs = $query->paginate($this->perPage);

        return view('livewire.log-ruangan', [
            'logs'   => $logs,
            'nowStr' => Carbon::now($this->tz)->format('d M Y, H:i') . ' WITA',
        ]);
    }
}
