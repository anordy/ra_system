@extends('layouts.master')

@section('title', 'Tax Audits')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Businesses With Risk Indicators
        </div>
        <div class="card-body">
            @livewire('audit.business-with-risk-indicators-table')
        </div>
    </div>
@endsection
