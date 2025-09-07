<div wire:poll.5s> {{-- auto refresh tiap 5 detik, ubah/hapus sesuai kebutuhan --}}

  {{-- Header judul & jam --}}
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      Log Aktivitas Mahasiswa - {{ \Illuminate\Support\Str::headline($ruangan) }}
    </h1>
    <div class="text-muted small">
      <i class="fas fa-clock"></i> {{ $nowStr }}
    </div>
  </div>

  {{-- Tombol Filter (tanpa reload page) --}}
  <div class="mb-4">
    <button class="btn btn-primary btn-filter {{ $filter==='masuk' ? 'active' : '' }}"
            wire:click="setFilter('masuk')">Log Masuk</button>
    <button class="btn btn-success btn-filter {{ $filter==='keluar' ? 'active' : '' }}"
            wire:click="setFilter('keluar')">Log Keluar</button>
    <button class="btn btn-secondary btn-filter {{ !$filter ? 'active' : '' }}"
            wire:click="setFilter">Log Gabungan</button>
  </div>

  {{-- Kartu Log --}}
  @forelse ($logs as $log)
    @php
      // copy logic dari blade kamu
      if ($filter === 'masuk') {
          $cardClass = 'bg-masuk';
          $status = '-';
      } elseif ($filter === 'keluar') {
          $cardClass = 'bg-sudah';
          $status = '-';
      } else {
          if ($log->waktu_masuk && !$log->waktu_keluar) {
              $status = 'Sedang di Kelas';
              $cardClass = 'bg-masuk';
          } elseif ($log->waktu_masuk && $log->waktu_keluar) {
              $status = 'Sudah Keluar';
              $cardClass = 'bg-sudah';
          } else {
              $status = '-';
              $cardClass = 'bg-masuk';
          }
      }

      $durasi = '-';
      if ($log->waktu_masuk && $log->waktu_keluar) {
          try {
              $durasi = \Carbon\Carbon::parse($log->waktu_masuk)
                  ->diff(\Carbon\Carbon::parse($log->waktu_keluar))
                  ->format('%H:%I:%S');
          } catch (\Exception $e) { $durasi = '-'; }
      }
    @endphp

    <div class="log-card {{ $cardClass }}">
      <div class="row">
        {{-- Kolom kiri --}}
        <div class="col-md-6 col-12">
          <div class="log-header">
            {{ $log->mahasiswa->nama }}<br>
            <small>{{ $log->mahasiswa->nim }} | Kelas: {{ $log->mahasiswa->kelas }}</small>
          </div>
          <div class="mt-2">
            @if (!$filter)
              @if ($status === 'Sudah Keluar')
                <span class="badge-status badge-sudah"><i class="fas fa-sign-out-alt"></i> {{ $status }}</span>
              @elseif ($status === 'Sedang di Kelas')
                <span class="badge-status badge-sedang"><i class="fas fa-map-marker-alt"></i> {{ $status }}</span>
              @endif
            @endif
          </div>
        </div>

        {{-- Kolom kanan --}}
        <div class="col-md-6 col-12">
          <div class="log-body">
            @if ($filter !== 'keluar')
              <div class="log-item">
                <i class="fas fa-sign-in-alt"></i> <strong>Masuk:</strong> {{ $log->waktu_masuk ?? '-' }}
              </div>
            @endif

            @if ($filter !== 'masuk')
              <div class="log-item">
                <i class="fas fa-sign-out-alt"></i> <strong>Keluar:</strong>
                @if ($log->waktu_keluar)
                  {{ $log->waktu_keluar }}
                @elseif($filter === 'keluar')
                  Belum scan masuk
                @else
                  Masih di kelas
                @endif
              </div>
            @endif

            @if (!$filter)
              <div class="log-item">
                <i class="fas fa-hourglass-half"></i> <strong>Durasi:</strong> {{ $durasi }}
              </div>
            @endif

            <div class="log-item">
              <i class="fas fa-calendar-alt"></i> <strong>Tanggal:</strong> {{ $log->tanggal }}
            </div>
          </div>
        </div>
      </div>
    </div>
  @empty
    <div class="alert alert-warning text-center">Tidak ada data log aktivitas.</div>
  @endforelse

  {{-- Pagination --}}
  @if ($logs->lastPage() > 1)
    <div class="card-footer">
      {{ $logs->onEachSide(1)->links('livewire::bootstrap') }}
    </div>
  @endif
</div>
