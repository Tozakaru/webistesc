<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LogAktivitas;
use Carbon\Carbon;

class LogRuangan extends Component
{
    use WithPagination;

    public string $ruangan = 'ruangan1';   // dioper dari view/route
    public ?string $filter = null;         // 'masuk' | 'keluar' | null (=gabungan)
    public int $perPage = 5;
    public string $tz = 'Asia/Makassar';

    protected $queryString = ['filter'];   // opsional: biar URL simpan tab/filter

    // Reset halaman saat filter berubah
    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function setFilter(?string $f = null)
    {
        $this->filter = $f;
    }

    public function render()
    {
        $today = Carbon::today($this->tz);

        $query = LogAktivitas::with('mahasiswa')
            ->where('ruangan', $this->ruangan)
            ->whereDate('tanggal', $today);

        if ($this->filter === 'masuk') {
            $query->whereNotNull('waktu_masuk');
        } elseif ($this->filter === 'keluar') {
            $query->whereNotNull('waktu_keluar');
        } // gabungan: tanpa tambahan

        $logs = $query->orderByDesc('waktu_masuk')->paginate($this->perPage);

        return view('livewire.log-ruangan', [
            'logs' => $logs,
            'nowStr' => Carbon::now($this->tz)->format('d M Y, H:i') . ' WITA',
        ]);
    }
}

