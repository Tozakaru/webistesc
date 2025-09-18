@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Data Dosen</h1>

  <div class="d-flex align-items-center" style="gap:8px; flex-wrap:nowrap;">
    <form method="GET" action="{{ route('dosen.index') }}" class="d-flex">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm"
             style="min-width:220px" placeholder="Cari nama / NIP / UID…">
      <button class="btn btn-sm btn-outline-secondary ms-1" type="submit">
        <i class="fas fa-search"></i>
      </button>
    </form>

    <button type="button" class="btn btn-sm btn-info shadow-sm"
            data-bs-toggle="modal" data-bs-target="#modalTambahDosen">
      <i class="fas fa-plus fa-sm text-white-50"></i> Tambah
    </button>
  </div>
</div>

@if(session('ok'))
  <div class="alert alert-success">{{ session('ok') }}</div>
@endif

@if($errors->any() && !session('edit_dosen_id'))
  <div id="modalCreateDosenErrorMarker" hidden></div>
@endif
@if(session('edit_dosen_id'))
  <div id="modalEditDosenErrorMarker"
       data-id="{{ session('edit_dosen_id') }}"
       data-nip="{{ old('nip') }}"
       data-nama="{{ old('nama') }}"
       data-jk="{{ old('jenis_kelamin') }}"
       data-uid="{{ old('uid_rfid') }}"
       data-status="{{ old('status_uid') ? 1 : 0 }}"
       hidden></div>
@endif

<div class="card shadow mb-4 card-border-highlight">
  <div class="card-body table-responsive">
    <table class="table table-bordered custom-table">
      <thead>
        <tr>
          <th>No</th>
          <th>NIP</th>
          <th>Nama</th>
          <th>Jenis Kelamin</th>
          <th>UID RFID</th>
          <th>Status</th>
          <th style="width:260px">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($dosens as $item)
          <tr>
            <td>{{ $dosens->firstItem() + $loop->index }}</td>
            <td>{{ $item->nip }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->jenis_kelamin }}</td>
            <td>{{ $item->uid_rfid }}</td>
            <td>
              @if ($item->status_uid)
                <span class="badge bg-success">Aktif</span>
              @else
                <span class="badge bg-danger">Nonaktif</span>
              @endif
            </td>
            <td>
              <div class="d-flex align-items-center justify-content-center" style="gap: 8px;">
                {{-- EDIT -> modal --}}
                <button type="button" class="btn btn-sm btn-warning"
                        data-bs-toggle="modal" data-bs-target="#modalEditDosen"
                        data-id="{{ $item->id }}"
                        data-nip="{{ $item->nip }}"
                        data-nama="{{ $item->nama }}"
                        data-jk="{{ $item->jenis_kelamin }}"
                        data-uid="{{ $item->uid_rfid }}"
                        data-status="{{ $item->status_uid ? 1 : 0 }}">
                  <i class="fas fa-edit"></i> Edit
                </button>

                {{-- Toggle UID --}}
                <form method="POST" action="{{ route('dosen.toggleUidStatus',$item->id) }}">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn btn-sm {{ $item->status_uid ? 'btn-outline-danger':'btn-outline-success' }}">
                    {{ $item->status_uid ? 'Nonaktifkan UID' : 'Aktifkan UID' }}
                  </button>
                </form>

                {{-- Hapus -> modal konfirmasi --}}
                <button type="button" class="btn btn-sm btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmationDeleteDosen-{{ $item->id }}">
                  <i class="fas fa-trash"></i> Hapus
                </button>
              </div>
            </td>
          </tr>

          @include('pages.Dosen.confirmation-delete', ['item' => $item])
        @empty
          <tr>
            <td colspan="7" class="text-center">
              <i class="fas fa-exclamation-circle"></i> Tidak ada data dosen.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($dosens->lastPage() > 1)
    <div class="card-footer">
      {{ $dosens->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

{{-- === include MODALS === --}}
@include('pages.Dosen.create')
@include('pages.Dosen.edit')

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  // buka modal TAMBah kalau validasi create gagal
  if (document.getElementById('modalCreateDosenErrorMarker')) {
    var addModal = new bootstrap.Modal(document.getElementById('modalTambahDosen'));
    addModal.show();
  }

  // isi modal EDIT saat ditampilkan
  var editModal = document.getElementById('modalEditDosen');
  editModal.addEventListener('show.bs.modal', function (event) {
    var btn  = event.relatedTarget;
    var id   = btn.getAttribute('data-id');
    var nip  = btn.getAttribute('data-nip')  || '';
    var nama = btn.getAttribute('data-nama') || '';
    var jk   = btn.getAttribute('data-jk')   || 'laki-laki';
    var uid  = btn.getAttribute('data-uid')  || '';
    var st   = (btn.getAttribute('data-status') === '1');

    var form = document.getElementById('formEditDosen');
    form.action = '/dosen/' + id;

    document.getElementById('edit_nip').value  = nip;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_jk').value   = jk;
    document.getElementById('edit_uid').value  = uid;
    document.getElementById('edit_status_uid').checked = st;
  });

  // reopen modal EDIT kalau validasi update gagal
  var marker = document.getElementById('modalEditDosenErrorMarker');
  if (marker) {
    var form = document.getElementById('formEditDosen');
    form.action = '/dosen/' + marker.dataset.id;

    document.getElementById('edit_nip').value  = marker.dataset.nip || '';
    document.getElementById('edit_nama').value = marker.dataset.nama || '';
    document.getElementById('edit_jk').value   = marker.dataset.jk || 'laki-laki';
    document.getElementById('edit_uid').value  = marker.dataset.uid || '';
    document.getElementById('edit_status_uid').checked = (marker.dataset.status === '1');

    new bootstrap.Modal(document.getElementById('modalEditDosen')).show();
  }
});
</script>
@endpush
