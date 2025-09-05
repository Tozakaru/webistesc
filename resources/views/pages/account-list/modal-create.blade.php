<div class="modal fade" id="modalCreateUser" tabindex="-1" aria-labelledby="modalCreateUserLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <input type="hidden" name="form_mode" value="create">

        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalCreateUserLabel">Buat Akun User</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup">
          <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="create_name">Nama</label>
                <input type="text" name="name" id="create_name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required>
                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="create_username">Username</label>
                <input type="text" name="username" id="create_username"
                       class="form-control @error('username') is-invalid @enderror"
                       value="{{ old('username') }}" placeholder="tanpa spasi" required>
                @error('username') <span class="invalid-feedback">{{ $message }}</span> @enderror
              </div>
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="create_password">Password</label>
            <input type="password" name="password" id="create_password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="min 8 karakter" required>
            @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="create_role">Role</label>
                <select name="role_id" id="create_role"
                        class="form-control @error('role_id') is-invalid @enderror" required>
                  @foreach ($roles as $id => $label)
                    <option value="{{ $id }}" @selected(old('role_id')==$id)>{{ $label }}</option>
                  @endforeach
                </select>
                @error('role_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="col-md-6 d-flex align-items-center">
              <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" name="is_active" id="create_is_active" value="1" @checked(old('is_active', true))>
                <label class="form-check-label" for="create_is_active">Aktifkan akun</label>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
