@extends('layouts.master')

@section('title', 'Tax Payer Report')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold">
            Tax Payer Report
        </div>
        <div class="card-body mt-0">
            @livewire('reports.tax-payer.tax-payer')
        </div>
    </div>
@endsection
