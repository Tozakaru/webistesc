<div wire:poll.5000ms>
  <div class="card shadow mb-4 section-card-invalid">
    <div class="card-body">
      @forelse ($logs as $log)
        <div class="log-card-invalid">
          <div class="log-info-box">
            <div class="log-info-item"><strong>UID:</strong> {{ $log->uid_rfid }}</div>
            <div class="log-info-item">
              <i class="fas fa-door-open"></i> <strong>Ruangan:</strong> {{ $log->ruangan ?? '-' }}
            </div>
            <div class="log-info-item">
              <i class="fas fa-clock"></i>
              <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($log->waktu)->format('d M Y, H:i:s') }}
            </div>
          </div>
        </div>
      @empty
        <div class="alert alert-warning text-center mb-0">Belum ada data aktivitas invalid</div>
      @endforelse
    </div>

    @if ($logs->lastPage() > 1)
      <div class="card-footer">
        {{ $logs->links('pagination::bootstrap-5') }}
      </div>
    @endif
  </div>
</div>
