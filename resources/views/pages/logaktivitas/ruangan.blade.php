@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('template/css/mahasiswa.css') }}">
@livewire('log-ruangan', ['ruangan' => $ruangan, 'filter' => request('filter')])
@endsection
