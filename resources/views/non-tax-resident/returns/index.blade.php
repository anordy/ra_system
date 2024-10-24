@extends('layouts.master')

@section('title', 'Registered Businesses')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Registered Businesses
        </div>
        <div class="card-body">
            <livewire:non-tax-resident.registrations-table />
        </div>
    </div>
@endsection
