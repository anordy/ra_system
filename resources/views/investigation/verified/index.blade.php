@extends('layouts.master')

@section('title', 'Tax Investigations')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Investigation Approved
        </div>
        <div class="card-body">
            @livewire('internal-info-change.internal-info-change-table')
        </div>
    </div>
@endsection
