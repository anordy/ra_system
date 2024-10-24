@extends('layouts.master')

@section('title', 'Registered Businesses Updates')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Registered Businesses Updates
        </div>
        <div class="card-body">
            <livewire:non-tax-resident.registration-updates-table />
        </div>
    </div>
@endsection
