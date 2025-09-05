@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('template/css/mahasiswa.css') }}">

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Daftar Akun User</h1>
  <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreateUser">
    + Buat Akun
  </button>
</div>

@if (session('success'))
  <script>
    Swal.fire({title:"Berhasil!", text:"{{ session('success') }}", icon:"success"});
  </script>
@endif
@if (session('error'))
  <script>
    Swal.fire({title:"Gagal!", text:"{{ session('error') }}", icon:"error"});
  </script>
@endif

<div class="row">
  <div class="col">
    <div class="card shadow mb-4 card-border-highlight">
      <div class="card-body table-responsive">

        <table class="table table-bordered custom-table">
          <thead>
            <tr>
              <th style="width:80px;">No</th>
              <th>Nama</th>
              <th>Username</th>
              <th>Role</th>
              <th>Status</th>
              <th style="width:260px;">Aksi</th>
            </tr>
          </thead>

          @if ($users->count() === 0)
            <tbody>
              <tr>
                <td colspan="6">
                  <p class="pt-3 text-center mb-0">Tidak ada data</p>
                </td>
              </tr>
            </tbody>
          @else
            <tbody>
              @foreach ($users as $item)
                <tr>
                  <td>{{ $loop->iteration + $users->firstItem() - 1 }}</td>
                  <td>{{ $item->name }}</td>
                  <td>{{ $item->username }}</td>
                  <td>{{ $item->role_id == 1 ? 'Admin' : 'User' }}</td>
                  <td>
                    @if ($item->is_active)
                      <span class="badge badge-success">Aktif</span>
                    @else
                      <span class="badge badge-danger">Tidak Aktif</span>
                    @endif
                  </td>
                  <td>
                    <div class="d-flex flex-wrap" style="gap:8px;">
                      {{-- Tombol Edit --}}
                      <button type="button"
                              class="btn btn-sm btn-outline-primary"
                              data-bs-toggle="modal"
                              data-bs-target="#modalEditUser"
                              data-id="{{ $item->id }}"
                              data-name="{{ $item->name }}"
                              data-username="{{ $item->username }}"
                              data-role="{{ $item->role_id }}"
                              data-active="{{ $item->is_active ? 1 : 0 }}">
                        Edit
                      </button>

                      @if ($item->is_active)
                        <button type="button" class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#confirmationReject-{{ $item->id }}">
                          Nonaktifkan
                        </button>
                      @else
                        <button type="button" class="btn btn-sm btn-outline-success"
                                data-bs-toggle="modal"
                                data-bs-target="#confirmationApprove-{{ $item->id }}">
                          Aktifkan
                        </button>
                      @endif

                      {{-- Tombol Hapus --}}
                      <form action="{{ route('users.destroy', $item) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Hapus akun ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-secondary">Hapus</button>
                      </form>
                    </div>
                  </td>
                </tr>
                @include('pages.account-list.confirmation-approve')
                @include('pages.account-list.confirmation-reject')
              @endforeach
            </tbody>
          @endif
        </table>
      </div>

      @if ($users->lastPage() > 1)
        <div class="card-footer">
          {{ $users->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>
  </div>
</div>

{{-- ====== Modal Create & Edit ====== --}}
@include('pages.account-list.modal-create')
@include('pages.account-list.modal-edit')

{{-- Auto-buka modal setelah validasi gagal --}}
@if ($errors->any())
  @if (old('form_mode') === 'edit')
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        new bootstrap.Modal('#modalEditUser').show()
      })
    </script>
  @else
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        new bootstrap.Modal('#modalCreateUser').show()
      })
    </script>
  @endif
@endif

@endsection
