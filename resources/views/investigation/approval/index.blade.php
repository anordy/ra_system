@extends('layouts.master')

@section('title', 'Investigations Approvals')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Investigations Approvals
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                    onclick="Livewire.emit('showModal', 'investigation.business-investigation-add-modal')">
                    <i class="fa fa-plus-circle"></i>
                    Add to Investigation
                </button>
            </div>
        </div>
        <div class="card-body">
            @livewire('approval.approval-count-card', ['modelName' => 'TaxInvestigation'])
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                        aria-selected="true">Pending Approval</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                        aria-selected="false">Initiate Approval</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active card p-2" id="home" role="tabpanel" aria-labelledby="home-tab">
                    @livewire('investigation.tax-investigation-approval-table')
                </div>
                <div class="tab-pane fade card p-2" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    @livewire('investigation.tax-investigation-initiate-table')
                </div>
            </div>
        </div>
    </div>
@endsection
