@extends('layouts.master')

@section('title','Land Leases')

@section('content')
    <div class="card">
        <div class="card-body">
           @livewire('land-lease.land-lease-list')
        </div>
    </div>
@endsection