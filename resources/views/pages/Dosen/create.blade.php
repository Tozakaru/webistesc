<div class="modal fade" id="modalTambahDosen" tabindex="-1" aria-labelledby="modalTambahDosenLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="{{ route('dosen.store') }}">
        @csrf
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title" id="modalTambahDosenLabel">Tambah Data Dosen</h5>
          <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal" aria-label="Close">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">NIP <small class="text-muted">(opsional)</small></label>
              <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}">
              @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-8">
              <label class="form-label">Nama Lengkap</label>
              <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}">
              @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">Jenis Kelamin</label>
              <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                <option value="laki-laki"  {{ old('jenis_kelamin')=='laki-laki' ? 'selected':'' }}>Laki-laki</option>
                <option value="perempuan"  {{ old('jenis_kelamin')=='perempuan' ? 'selected':'' }}>Perempuan</option>
              </select>
              @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-8">
              <label class="form-label">UID RFID</label>
              <input type="text" name="uid_rfid" class="form-control @error('uid_rfid') is-invalid @enderror" value="{{ old('uid_rfid') }}">
              @error('uid_rfid') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="status_uid" id="status_uid_create" value="1" {{ old('status_uid', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="status_uid_create">Aktifkan UID</label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-info">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
