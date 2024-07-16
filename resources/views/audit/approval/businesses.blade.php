@extends('layouts.master')

@section('title', 'Tax Audits')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Businesses With Risk Indicators
            <div class="card-tools">
                @can('itu-add-business-to-audit')
                    <button class="btn btn-primary btn-sm" onclick="Livewire.emit('showModal', 'audit.business-audit-add-modal', null, true)">
                        <i class="bi bi-plus-circle-fill mr-1"></i>
                        Add Business To Audit
                    </button>
                @endcan
            </div>
        </div>
        <div class="card-body">
            @livewire('audit.business-with-risk-table')
{{--            @livewire('audit.business-with-risk-indicators-table')--}}
        </div>
    </div>
@endsection
