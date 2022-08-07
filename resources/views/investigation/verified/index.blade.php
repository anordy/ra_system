@extends('layouts.master')

@section('title', 'Tax Investigations')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Investigation Approved
        </div>
        <div class="card-body">
            @livewire('investigation.tax-investigation-verified-table')
        </div>
    </div>
@endsection
