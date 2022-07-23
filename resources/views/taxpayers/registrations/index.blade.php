@extends('layouts.master')

@section('title', 'KYC Requests')

@section('content')
    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            KYC Requests
        </div>
        <div class="card-body">
            <livewire:taxpayers.registrations-table />
        </div>
    </div>
@endsection
