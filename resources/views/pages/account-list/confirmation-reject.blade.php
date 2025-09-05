<div class="modal fade" id="confirmationReject-{{ $item->id }}" tabindex="-1"
     aria-labelledby="confirmationRejectLabel-{{ $item->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('users.deactivate', $item) }}" method="POST">
      @csrf @method('PATCH')
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title fs-5" id="confirmationRejectLabel-{{ $item->id }}">Konfirmasi Non-Aktif</h4>
          <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="modal-body">
          Apakah Anda yakin ingin menonaktifkan akun <strong>{{ $item->name }}</strong>?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-outline-danger">Ya, Nonaktifkan!</button>
        </div>
      </div>
    </form>
  </div>
</div>
