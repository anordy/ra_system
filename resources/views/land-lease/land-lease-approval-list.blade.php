@extends('layouts.master')

@section('title','Land Leases Payment Approve')

@section('content')
    <div class="card">
        <div class="card-body">
           @livewire('land-lease.land-lease-approve-list')
        </div>
    </div>
@endsection