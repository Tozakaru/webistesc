<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Mahasiswa;

class MahasiswaTable extends Component
{
    use WithPagination;

    /** Livewire v2 & v3 friendly */
    public $search = '';
    public $perPage = 7;

    // Simpan ke URL
    protected $queryString = ['search'];

    // Theme pagination (opsional)
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        // Supaya balik ke halaman 1 saat kata kunci berubah
        $this->resetPage();
    }

    public function render()
    {
        $keyword = trim((string) $this->search);

        $mahasiswas = Mahasiswa::query()
            ->when($keyword !== '', function ($q) use ($keyword) {
                $s = "%{$keyword}%";
                $q->where(function ($sub) use ($s) {
                    $sub->where('nama', 'like', $s)
                        ->orWhere('nim', 'like', $s)
                        ->orWhere('kelas', 'like', $s)
                        ->orWhere('uid_rfid', 'like', $s)
                        ->orWhere('jenis_kelamin', 'like', $s);
                });
            })
            ->orderBy('nim')
            ->paginate($this->perPage)
            ->withQueryString();

        return view('livewire.mahasiswa-table', compact('mahasiswas'));
    }
}
