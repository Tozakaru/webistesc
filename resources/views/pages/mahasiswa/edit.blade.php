<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formEdit" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header bg-warning">
          <h5 class="modal-title" id="modalEditLabel">Ubah Data Mahasiswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup">
          <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="modal-body">
          <div class="form-group mb-3">
            <label for="edit_nim">NIM</label>
            <input type="number" id="edit_nim" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}">
            @error('nim') <span class="invalid-feedback">{{ $message }}</span> @enderror
          </div>

          <div class="form-group mb-3">
            <label for="edit_nama">Nama Lengkap</label>
            <input type="text" id="edit_nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}">
            @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
          </div>

          <div class="form-group mb-3">
            <label for="edit_jk">Jenis Kelamin</label>
            <select id="edit_jk" name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
              <option value="laki-laki" {{ old('jenis_kelamin')=='laki-laki'?'selected':'' }}>Laki-laki</option>
              <option value="perempuan" {{ old('jenis_kelamin')=='perempuan'?'selected':'' }}>Perempuan</option>
            </select>
            @error('jenis_kelamin') <span class="invalid-feedback">{{ $message }}</span> @enderror
          </div>

          <div class="form-group mb-3">
            <label for="edit_kelas">Kelas</label>
            <input type="text" id="edit_kelas" name="kelas" class="form-control @error('kelas') is-invalid @enderror" value="{{ old('kelas') }}">
            @error('kelas') <span class="invalid-feedback">{{ $message }}</span> @enderror
          </div>

          <div class="form-group mb-3">
            <label for="edit_uid">UID RFID</label>
            <input type="text" id="edit_uid" name="uid_rfid" class="form-control @error('uid_rfid') is-invalid @enderror" value="{{ old('uid_rfid') }}">
            @error('uid_rfid') <span class="invalid-feedback">{{ $message }}</span> @enderror
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
