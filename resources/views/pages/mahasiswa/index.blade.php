@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('template/css/mahasiswa.css') }}">
@livewire('mahasiswa-table')
@endsection
