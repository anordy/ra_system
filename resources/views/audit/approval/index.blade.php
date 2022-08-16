@extends('layouts.master')

@section('title', 'Tax Audits Approvals')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Audit Approvals
            <div class="card-tools">
                <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'audit.business-audit-add-modal')">
                    <i class="fa fa-plus-circle"></i>
                    Add To Audit
                </button>
            </div>
        </div>
        <div class="card-body">
            @livewire('audit.tax-audit-approval-table')
        </div>
    </div>
@endsection
