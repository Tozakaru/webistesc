@extends('layouts.app')
@section('content')

<link href="https://fonts.googleapis.com/css2?family=League+Spartan&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('template/css/mahasiswa.css') }}">

<style>
  /* opsional: bikin input tanggal tidak terlalu panjang di desktop */
  @media (min-width: 992px) {
    .date-narrow { max-width: 100%; }   /* biar ikut lebar kolom (col-lg-2) */
  }
</style>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Rekapan Aktivitas</h1>

  {{-- Filter --}}
  <form method="GET" action="{{ route('rekapan.index') }}" class="mb-4">
    <div class="row g-3 align-items-end">
      <div class="col-12 col-md-6 col-lg-2">
        <label for="start_date" class="form-label">Dari Tanggal</label>
        <input type="date" name="start_date" id="start_date" class="form-control date-narrow"
               value="{{ request('start_date') }}">
      </div>

      <div class="col-12 col-md-6 col-lg-2">
        <label for="end_date" class="form-label">Sampai Tanggal</label>
        <input type="date" name="end_date" id="end_date" class="form-control date-narrow"
               value="{{ request('end_date') }}">
      </div>

      <div class="col-12 col-md-6 col-lg-2">
        <label for="role" class="form-label">Kategori</label>
        <select name="role" id="role" class="form-control">
          <option value="all"       {{ request('role','all')==='all' ? 'selected':'' }}>Semua</option>
          <option value="mahasiswa" {{ request('role')==='mahasiswa' ? 'selected':'' }}>Mahasiswa</option>
          <option value="dosen"     {{ request('role')==='dosen' ? 'selected':'' }}>Dosen</option>
        </select>
      </div>

      <div class="col-12 col-md-6 col-lg-4">
        <label for="person" class="form-label">Pilih Nama (opsional)</label>
        <select name="person" id="person" class="form-control">
          <option value="">— Semua Orang —</option>
          <optgroup label="Mahasiswa">
            @foreach($mhsList as $m)
              @php $val = 'm:'.$m->id; @endphp
              <option value="{{ $val }}" {{ request('person')===$val ? 'selected':'' }}>
                {{ $m->nama }} ({{ $m->nim }})
              </option>
            @endforeach
          </optgroup>
          <optgroup label="Dosen">
            @foreach($dsnList as $d)
              @php $val = 'd:'.$d->id; @endphp
              <option value="{{ $val }}" {{ request('person')===$val ? 'selected':'' }}>
                {{ $d->nama }} ({{ $d->nip ?? '—' }})
              </option>
            @endforeach
          </optgroup>
        </select>
      </div>

      {{-- Tombol sejajar dan ada jarak --}}
      <div class="col-12 col-lg-2 d-flex gap-2">
        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
        <a href="{{ route('rekapan.index') }}" class="btn btn-secondary w-100">Reset</a>
      </div>
    </div>
  </form>

  {{-- Info filter --}}
  @if($showTable)
    <div class="alert alert-info">
      <strong>Rentang:</strong> <u>{{ request('start_date') }}</u> s.d. <u>{{ request('end_date') }}</u>
      @if(request('role') && request('role')!=='all')
        — <strong>Kategori:</strong> {{ ucfirst(request('role')) }}
      @endif
      @if(request('person'))
        — <strong>Nama:</strong>
        @php
          $p = request('person'); $label = '';
          if (str_starts_with($p,'m:')) {
              $id = (int)substr($p,2);
              $mm = $mhsList->firstWhere('id',$id);
              if ($mm) $label = $mm->nama . ' ('.$mm->nim.')';
          } elseif (str_starts_with($p,'d:')) {
              $id = (int)substr($p,2);
              $dd = $dsnList->firstWhere('id',$id);
              if ($dd) $label = $dd->nama . ' ('.$dd->nip.')';
          }
        @endphp
        {{ $label ?: '-' }}
      @endif
    </div>
  @endif

  {{-- Tabel --}}
  @if($showTable)
    <div class="card shadow mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-dark">Daftar Aktivitas</h5>

        <div class="btn-group">
          <form method="GET" action="{{ route('rekapan.export.csv') }}" class="d-inline me-2">
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date"   value="{{ request('end_date') }}">
            <input type="hidden" name="role"       value="{{ request('role','all') }}">
            <input type="hidden" name="person"     value="{{ request('person') }}">
            <button type="submit" class="btn btn-success btn-sm">
              <i class="fas fa-file-csv"></i> CSV
            </button>
          </form>

          <form method="GET" action="{{ route('rekapan.export.pdf') }}" class="d-inline">
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date"   value="{{ request('end_date') }}">
            <input type="hidden" name="role"       value="{{ request('role','all') }}">
            <input type="hidden" name="person"     value="{{ request('person') }}">
            <button type="submit" class="btn btn-danger btn-sm">
              <i class="fas fa-file-pdf"></i> PDF
            </button>
          </form>
        </div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered custom-table">
            <thead class="text-center">
            <tr>
              <th>No</th>
              <th>Role</th>
              <th>Nama</th>
              <th>NIM/NIP</th>
              <th>Tanggal</th>
              <th>Ruangan</th>
              <th>Waktu Masuk</th>
              <th>Waktu Keluar</th>
            </tr>
            </thead>
            <tbody>
            @forelse($logs as $i => $log)
              @php
                $isMhs = !is_null($log->mahasiswa_id);
                $role  = $isMhs ? 'Mahasiswa' : 'Dosen';
                $nama  = $isMhs ? ($log->mahasiswa->nama ?? '-') : ($log->dosen->nama ?? '-');
                $idno  = $isMhs ? ($log->mahasiswa->nim  ?? '-') : ($log->dosen->nip   ?? '-');
              @endphp
              <tr>
                <td class="text-center">{{ $logs->firstItem() + $i }}</td>
                <td class="text-center">{{ $role }}</td>
                <td>{{ $nama }}</td>
                <td class="text-center">{{ $idno }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($log->tanggal)->format('d/m/Y') }}</td>
                <td class="text-center">{{ $log->ruangan }}</td>
                <td class="text-center">{{ $log->waktu_masuk  ? \Carbon\Carbon::parse($log->waktu_masuk)->format('H:i:s')  : '-' }}</td>
                <td class="text-center">{{ $log->waktu_keluar ? \Carbon\Carbon::parse($log->waktu_keluar)->format('H:i:s') : '-' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center">Tidak ada data untuk filter ini.</td>
              </tr>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if ($logs->lastPage() > 1)
        <div class="card-footer">
          {{ $logs->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>
  @else
    <div class="card shadow mb-4">
      <div class="card-body text-center text-muted">
        Silakan pilih <strong>Dari Tanggal</strong> dan <strong>Sampai Tanggal</strong> terlebih dahulu.
      </div>
    </div>
  @endif
</div>
@endsection
