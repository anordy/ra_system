@extends('layouts.master')

@section('title', 'Business De-registration Requests')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            De-registrations Request
        </div>
        <div class="card-body">
            <livewire:non-tax-resident.de-registrations-table />
        </div>
    </div>
@endsection
