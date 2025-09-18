@extends('layouts.app')

@section('content')
  {{-- dari route /log/{ruangan} atau route spesifik, variabel $ruangan = 'ruangan1'/'ruangan2' --}}
  <livewire:log-ruangan :ruangan-code="$ruangan" />
@endsection

