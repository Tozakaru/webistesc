<div class="modal fade" id="confirmationApprove-{{ $item->id }}" tabindex="-1"
     aria-labelledby="confirmationApproveLabel-{{ $item->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('users.activate', $item) }}" method="POST">
      @csrf @method('PATCH')
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title fs-5" id="confirmationApproveLabel-{{ $item->id }}">Konfirmasi Aktifkan</h4>
          <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="modal-body">
          Apakah Anda yakin ingin mengaktifkan akun <strong>{{ $item->name }}</strong>?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-success">Ya, Aktifkan!</button>
        </div>
      </div>
    </form>
  </div>
</div>
