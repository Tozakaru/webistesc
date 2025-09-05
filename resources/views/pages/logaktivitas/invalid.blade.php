@extends('layouts.app')

@section('title', 'Aktivitas Invalid')

@section('content')
<link rel="stylesheet" href="{{ asset('template/css/mahasiswa.css') }}">

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Aktivitas Invalid (UID Tidak Terdaftar)</h1>
  <div class="text-muted small">
    <i class="fas fa-clock"></i>
    <span id="live-clock">{{ \Carbon\Carbon::now('Asia/Makassar')->format('d M Y, H:i') }} WITA</span>
  </div>
</div>

@livewire('invalid-logs')
@endsection
