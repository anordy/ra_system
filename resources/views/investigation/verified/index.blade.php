@extends('layouts.master')

@section('title', 'Audit Investigation')

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
