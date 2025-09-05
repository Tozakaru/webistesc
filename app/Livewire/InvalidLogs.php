<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LogInvalid;

class InvalidLogs extends Component
{
    use WithPagination;

    // pakai pagination bootstrap (atau hapus baris ini jika default Tailwind)
    protected $paginationTheme = 'bootstrap';

    public int $perPage = 6;

    // auto reset ke halaman 1 kalau nanti ada filter2 tambahan
    public function updating($name, $value) { $this->resetPage(); }

    public function render()
    {
        $logs = LogInvalid::orderByDesc('waktu')
            ->paginate($this->perPage);

        return view('livewire.invalid-logs', compact('logs'));
    }
}
