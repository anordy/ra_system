@extends('layouts.master')

@section('title', 'Reconciliation Report')

@section('content')
    <div class="card rounded-0">
        <div class="card-header font-weight-bold text-uppercase bg-white">
            Reconciliation Report
        </div>
        <div class="card-body">
            @livewire('payments.recon-report-filter')
        </div>
    </div>

@endsection
