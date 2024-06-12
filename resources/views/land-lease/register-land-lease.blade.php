@extends('layouts.master')

@section('title','Register New Land Lease')

@section('content')
    <div class="card">
        <div class="card-body">
           @livewire('land-lease.register-land-lease')
        </div>
    </div>
@endsection