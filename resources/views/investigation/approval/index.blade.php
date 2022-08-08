@extends('layouts.master')

@section('title', 'Tax Investigation Approvals')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Investigation Approvals
        </div>
        <div class="card-body">
            @livewire('investigation.tax-investigation-approval-table')
        </div>
    </div>
@endsection
