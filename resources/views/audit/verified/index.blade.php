@extends('layouts.master')

@section('title', 'Audit Approved')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Audits Approved
        </div>
        <div class="card-body">
            @livewire('audit.tax-audit-verified-table')
        </div>
    </div>
@endsection
