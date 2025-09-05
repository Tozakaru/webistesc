<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{ url('/mahasiswa') }}" method="POST">
        @csrf
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title" id="modalTambahLabel">Tambah Data Mahasiswa</h5>
          <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal" aria-label="Close">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="modal-body">
          <div class="form-group mb-3">
            <label for="create_nim">NIM</label>
            <input type="number" name="nim" id="create_nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}">
            @error('nim') <span class="invalid-feedback">{{ $message }}</span> @enderror
          </div>

          <div class="form-group mb-3">
            <label for="create_nama">Nama Lengkap</label>
            <input type="text" name="nama" id="create_nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}">
            @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
          </div>

          <div class="form-group mb-3">
            <label for="create_jk">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="create_jk" class="form-control @error('jenis_kelamin') is-invalid @enderror">
              <option value="laki-laki" {{ old('jenis_kelamin')=='laki-laki'?'selected':'' }}>Laki-laki</option>
              <option value="perempuan" {{ old('jenis_kelamin')=='perempuan'?'selected':'' }}>Perempuan</option>
            </select>
            @error('jenis_kelamin') <span class="invalid-feedback">{{ $message }}</span> @enderror
          </div>

          <div class="form-group mb-3">
            <label for="create_kelas">Kelas</label>
            <input type="text" name="kelas" id="create_kelas" class="form-control @error('kelas') is-invalid @enderror" value="{{ old('kelas') }}">
            @error('kelas') <span class="invalid-feedback">{{ $message }}</span> @enderror
          </div>

          <div class="form-group mb-3">
            <label for="create_uid">UID RFID</label>
            <input type="text" name="uid_rfid" id="create_uid" class="form-control @error('uid_rfid') is-invalid @enderror" value="{{ old('uid_rfid') }}">
            @error('uid_rfid') <span class="invalid-feedback">{{ $message }}</span> @enderror
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
