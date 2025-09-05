<div>
  {{-- Header: judul kiri, kanan = Tambah + Search --}}
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Data Mahasiswa</h1>

  {{-- kanan: Search (Livewire) lalu tombol Tambah --}}
  <div class="d-flex align-items-center" style="gap:8px; flex-wrap:nowrap;">
    <input type="text"
           class="form-control form-control-sm"
           style="min-width:220px"
           placeholder="Cari nama / NIM / kelas / UID…"
           @if (class_exists(\Livewire\Mechanisms\ComponentRegistry::class))
             wire:model.live="search"
           @else
             wire:model.debounce.300ms="search"
           @endif
    >

    {{-- Tombol Tambah --}}
    <button class="btn btn-sm btn-info shadow-sm"
            data-bs-toggle="modal" data-bs-target="#modalTambah">
      <i class="fas fa-plus fa-sm text-white-50"></i> Tambah
    </button>
  </div>
</div>


  {{-- Marker error (CREATE) --}}
  @if($errors->any() && !session('edit_id'))
    <div id="modalCreateErrorMarker" hidden></div>
  @endif

  {{-- Marker error (EDIT) --}}
  @if(session('edit_id'))
    <div id="modalEditErrorMarker"
         data-id="{{ session('edit_id') }}"
         data-nim="{{ old('nim') }}"
         data-nama="{{ old('nama') }}"
         data-jk="{{ old('jenis_kelamin') }}"
         data-kelas="{{ old('kelas') }}"
         data-uid="{{ old('uid_rfid') }}"
         hidden></div>
  @endif

  <div class="card shadow mb-4 card-border-highlight">
    <div class="card-body table-responsive">
      <table class="table table-bordered custom-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nim</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
            <th>Kelas</th>
            <th>UID RFID</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>

        @if ($mahasiswas->count() < 1)
          <tbody>
            <tr>
              <td colspan="8" class="no-data text-center">
                <i class="fas fa-exclamation-circle"></i> Tidak ada data mahasiswa yang ditemukan.
              </td>
            </tr>
          </tbody>
        @else
          <tbody>
            @foreach ($mahasiswas as $item)
              <tr>
                <td>{{ $mahasiswas->firstItem() + $loop->index }}</td>
                <td>{{ $item->nim }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->jenis_kelamin }}</td>
                <td>{{ $item->kelas }}</td>
                <td>{{ $item->uid_rfid }}</td>
                <td>
                  @if ($item->status_uid)
                    <span class="badge bg-success text-white">Aktif</span>
                  @else
                    <span class="badge bg-danger text-white">Nonaktif</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex align-items-center justify-content-center" style="gap: 8px;">
                    <button type="button" class="btn btn-sm btn-warning btn-icon"
                            data-bs-toggle="modal" data-bs-target="#modalEdit"
                            data-id="{{ $item->id }}"
                            data-nim="{{ $item->nim }}"
                            data-nama="{{ $item->nama }}"
                            data-jk="{{ $item->jenis_kelamin }}"
                            data-kelas="{{ $item->kelas }}"
                            data-uid="{{ $item->uid_rfid }}">
                      <i class="fas fa-edit"></i> Edit
                    </button>

                    <form method="POST" action="{{ route('mahasiswa.toggleUidStatus', $item->id) }}">
                      @csrf @method('PATCH')
                      <button type="submit"
                              class="btn btn-sm {{ $item->status_uid ? 'btn-outline-danger' : 'btn-outline-success' }}">
                        <i class="fas fa-ban"></i> {{ $item->status_uid ? 'Nonaktifkan UID' : 'Aktifkan UID' }}
                      </button>
                    </form>

                    <button type="button" class="btn btn-sm btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmationDelete-{{ $item->id }}">
                      <i class="fas fa-trash"></i> Hapus
                    </button>
                  </div>
                </td>
              </tr>

              {{-- Modal konfirmasi hapus per-item --}}
              @include('pages.mahasiswa.confirmation-delete', ['mahasiswa' => $item])
            @endforeach
          </tbody>
        @endif
      </table>
    </div>

    @if ($mahasiswas->lastPage() > 1)
      <div class="card-footer">
        {{ $mahasiswas->links('pagination::bootstrap-5') }}
      </div>
    @endif
  </div>

  {{-- Modal Tambah & Edit --}}
  @include('pages.mahasiswa.create')
  @include('pages.mahasiswa.edit')

  <script src="{{ asset('template/js/demo/mhs.js') }}"></script>
</div>
