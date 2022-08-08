@extends('layouts.master')

@section('title', 'Tax Audits Approvals')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Audit Approvals
        </div>
        <div class="card-body">
            @livewire('audit.tax-audit-approval-table')
        </div>
    </div>
@endsection
