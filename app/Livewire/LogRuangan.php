<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EspDevice;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogRuangan extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // input dari route/page
    public string $ruangan;      // akan diisi "SmartClass 1/2" untuk header
    public string $deviceCode;   // "ruangan1" | "ruangan2"

    // state internal
    public int $deviceId;
    public ?string $filter = null;   // 'masuk' | 'keluar' | null
    public string $tz = 'Asia/Makassar';

    // dipanggil dari parent page: <livewire:log-ruangan :ruangan-code="$ruangan" />
    public function mount(string $ruanganCode)
    {
        // ruanganCode dari route adalah slug "ruangan1"/"ruangan2"
        $device = EspDevice::where('code', $ruanganCode)
            ->orWhere('nama_kelas', $ruanganCode) // fallback kalau ada yang kirim "SmartClass 1"
            ->firstOrFail();

        $this->deviceId   = (int) $device->id;
        $this->deviceCode = $device->code;
        $this->ruangan    = $device->nama_kelas; // untuk judul tampilan
    }

    public function setFilter($value = null)
    {
        $this->filter = $value ?: null;
        $this->resetPage();
    }

    public function getNowStrProperty(): string
    {
        return now($this->tz)->format('d M Y, H:i') . ' WITA';
    }

    public function render()
    {
        $today = Carbon::today();

        $query = LogAktivitas::with(['mahasiswa','dosen'])
            ->where('esp_device_id', $this->deviceId)
            ->whereDate('tanggal', $today);

        if ($this->filter === 'masuk')  $query->whereNotNull('waktu_masuk');
        if ($this->filter === 'keluar') $query->whereNotNull('waktu_keluar');

        $logs = $query
            ->orderByDesc(DB::raw('COALESCE(waktu_keluar, waktu_masuk)'))
            ->paginate(5);

        return view('livewire.log-ruangan', [
            'logs'   => $logs,
            'ruangan'=> $this->ruangan,
            'tz'     => $this->tz,
            'nowStr' => $this->nowStr,
            'filter' => $this->filter,
        ]);
    }
}
