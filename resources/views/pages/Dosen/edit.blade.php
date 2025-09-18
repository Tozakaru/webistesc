<div class="modal fade" id="modalEditDosen" tabindex="-1" aria-labelledby="modalEditDosenLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formEditDosen" method="POST">
        @csrf @method('PUT')
        <div class="modal-header bg-warning">
          <h5 class="modal-title" id="modalEditDosenLabel">Ubah Data Dosen</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">NIP <small class="text-muted">(opsional)</small></label>
              <input type="text" id="edit_nip" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}">
              @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-8">
              <label class="form-label">Nama Lengkap</label>
              <input type="text" id="edit_nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}">
              @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">Jenis Kelamin</label>
              <select id="edit_jk" name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                <option value="laki-laki">Laki-laki</option>
                <option value="perempuan">Perempuan</option>
              </select>
              @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-8">
              <label class="form-label">UID RFID</label>
              <input type="text" id="edit_uid" name="uid_rfid" class="form-control @error('uid_rfid') is-invalid @enderror" value="{{ old('uid_rfid') }}">
              @error('uid_rfid') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="edit_status_uid" name="status_uid" value="1">
                <label class="form-check-label" for="edit_status_uid">Aktifkan UID</label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
