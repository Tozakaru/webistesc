<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogRuangan extends Component
{
    use WithPagination;

    public string $ruangan = 'ruangan1';
    public ?string $filter = null;    // 'masuk' | 'keluar' | null (=gabungan)
    public int $perPage = 5;
    public string $tz = 'Asia/Makassar';

    protected $queryString = ['filter'];

    public function updatedFilter()  { $this->resetPage(); }
    public function updatedRuangan() { $this->resetPage(); }

    public function setFilter(?string $f = null)
    {
        $this->filter = $f;
    }

    public function render()
    {
        $tz         = $this->tz;
        // tanggal hari ini pakai zona lokal (kolom 'tanggal' bertipe DATE)
        $todayLocal = Carbon::now($tz)->toDateString(); // 'YYYY-MM-DD'

        $query = LogAktivitas::with([
                    'mahasiswa:id,nama,nim,kelas',
                    'dosen:id,nama,nip',
                ])
                ->where('ruangan', $this->ruangan)
                ->whereDate('tanggal', $todayLocal);

        // Filter & urutan terbaru
        if ($this->filter === 'masuk') {
            $query->whereNotNull('waktu_masuk')
                  ->orderByDesc('waktu_masuk');
        } elseif ($this->filter === 'keluar') {
            $query->whereNotNull('waktu_keluar')
                  ->orderByDesc('waktu_keluar');
        } else {
            $query->orderByRaw('COALESCE(waktu_keluar, waktu_masuk) DESC')
                  ->orderByDesc('id');
        }

        $logs = $query->paginate($this->perPage);

        return view('livewire.log-ruangan', [
            'logs'    => $logs,
            'nowStr'  => Carbon::now($tz)->format('d M Y, H:i') . ' WITA',
            'tz'      => $tz,
            'ruangan' => $this->ruangan,
            'filter'  => $this->filter,
        ]);
    }
}
