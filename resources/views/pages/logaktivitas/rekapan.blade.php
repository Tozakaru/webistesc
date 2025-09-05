@extends('layouts.app')
@section('content')

<link href="https://fonts.googleapis.com/css2?family=League+Spartan&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('template/css/mahasiswa.css') }}">

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Rekapan Aktivitas Mahasiswa</h1>

    {{-- Filter --}}
    <form method="GET" action="{{ route('rekapan.index') }}" class="mb-4">
        <div class="form-row">
            <div class="col-md-3 mb-2">
                <label for="start_date">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" class="form-control"
                       value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3 mb-2">
                <label for="end_date">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" class="form-control"
                       value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 mb-2">
                <label for="q">Cari Nama / NIM</label>
                <input type="text" name="q" id="q" class="form-control" placeholder="Semua Mahasiswa"
                       value="{{ request('q') }}">
            </div>
            <div class="col-md-2 mb-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary mr-2">Tampilkan</button>
                <a href="{{ route('rekapan.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    {{-- Info filter --}}
    @if($showTable)
        <div class="alert alert-info">
            <strong>Rentang:</strong> <u>{{ request('start_date') }}</u> s.d. <u>{{ request('end_date') }}</u>
            @if(request('q')) — <strong>Kata kunci:</strong> “{{ request('q') }}” @endif
        </div>
    @endif

    {{-- Tabel (hanya tampil jika tanggal terisi) --}}
    @if($showTable)
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-dark">Daftar Aktivitas</h5>
                <div class="btn-group">
                    <form method="GET" action="{{ route('rekapan.export.csv') }}" class="d-inline mr-2">
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-file-csv"></i> CSV</button>
                    </form>
                    <form method="GET" action="{{ route('rekapan.export.pdf') }}" class="d-inline">
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf"></i> PDF</button>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered custom-table">
                        <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Tanggal</th>
                            <th>Ruangan</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Keluar</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($logs as $index => $log)
                            <tr>
                                <td class="text-center">{{ $logs->firstItem() + $index }}</td>
                                <td>{{ $log->mahasiswa->nama }}</td>
                                <td>{{ $log->mahasiswa->nim }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->tanggal)->format('d/m/Y') }}</td>
                                <td>{{ $log->ruangan }}</td>
                                <td class="text-center">{{ $log->waktu_masuk ?? '-' }}</td>
                                <td class="text-center">{{ $log->waktu_keluar ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data untuk filter ini.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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
