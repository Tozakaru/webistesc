<div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="editUserForm"
            action="{{ old('form_mode')==='edit' && old('id') ? route('users.update', old('id')) : route('users.update', 0) }}"
            method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title" id="modalEditUserLabel">Edit Akun</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup">
          <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="form_mode" value="edit">
          <input type="hidden" name="id" id="edit_id" value="{{ old('id') }}">

          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="edit_name">Nama</label>
                <input type="text" name="name" id="edit_name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required>
                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="edit_username">Username</label>
                <input type="text" name="username" id="edit_username"
                       class="form-control @error('username') is-invalid @enderror"
                       value="{{ old('username') }}" required>
                @error('username') <span class="invalid-feedback">{{ $message }}</span> @enderror
              </div>
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="edit_password">Password</label>
            <input type="text" name="password" id="edit_password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Kosongkan jika tidak diganti">
            @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="edit_role">Role</label>
                <select name="role_id" id="edit_role"
                        class="form-control @error('role_id') is-invalid @enderror" required>
                  @foreach ($roles as $id => $label)
                    <option value="{{ $id }}" {{ (string)old('role_id') === (string)$id ? 'selected' : '' }}>{{ $label }}</option>
                  @endforeach
                </select>
                @error('role_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="col-md-6 d-flex align-items-center">
              <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active" value="1"
                       {{ old('form_mode')==='edit' ? (old('is_active') ? 'checked' : '') : '' }}>
                <label class="form-check-label" for="edit_is_active">Akun Aktif</label>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning text-white">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- JS isi data saat tombol Edit ditekan --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  var modalEl = document.getElementById('modalEditUser');
  if (!modalEl) return;

  modalEl.addEventListener('show.bs.modal', function (event) {
    var btn = event.relatedTarget;
    if (!btn) return;

    var id = btn.getAttribute('data-id');
    var name = btn.getAttribute('data-name') || '';
    var username = btn.getAttribute('data-username') || '';
    var role = btn.getAttribute('data-role') || '2';
    var active = btn.getAttribute('data-active') === '1';

    // set action form: ambil URL template lalu ganti /0 di belakang jadi /{id}
    var form = document.getElementById('editUserForm');
    var actionTemplate = form.getAttribute('action'); // ex: /account-list/0
    form.setAttribute('action', actionTemplate.replace(/\/0$/, '/' + id));

    // isi field
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_role').value = role;
    document.getElementById('edit_is_active').checked = active;

    // kosongkan password setiap buka modal
    var pwd = document.getElementById('edit_password');
    if (pwd) pwd.value = '';
  });
});
</script>
