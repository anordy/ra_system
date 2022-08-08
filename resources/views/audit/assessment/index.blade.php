@extends('layouts.master')

@section('title', 'Audits with Assessment')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Audits with Assessements
        </div>
        <div class="card-body">
            @livewire('audit.tax-audit-assessment-table')
        </div>
    </div>
@endsection
