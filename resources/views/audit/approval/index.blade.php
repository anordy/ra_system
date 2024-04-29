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
            @livewire('approval.approval-count-card', ['modelName' => 'TaxAudit'])
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                {{-- <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Businesses with risk indicators</a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                        aria-controls="profile" aria-selected="false">Businesses Added to Audit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Pending Approval</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="progress-tab" data-toggle="tab" href="#progress" role="tab"
                        aria-controls="profile" aria-selected="false">Approval Progress</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                {{-- <div class="tab-pane fade show active card p-2" id="home" role="tabpanel" aria-labelledby="home-tab">
                    @livewire('audit.business-with-risk-indicators-table')
                </div> --}}
                <div class="tab-pane fade show active card p-2" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    @livewire('audit.tax-audit-initiate-table')
                </div>
                <div class="tab-pane fade show card p-2" id="home" role="tabpanel" aria-labelledby="home-tab">
                    @livewire('audit.tax-audit-approval-table')
                </div>
                <div class="tab-pane fade card p-2" id="progress" role="tabpanel" aria-labelledby="progress-tab">
                    @livewire('audit.tax-audit-approval-progress-table')
                </div>  
            </div>
        </div>
    </div>
@endsection
