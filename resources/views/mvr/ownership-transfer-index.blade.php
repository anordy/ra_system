@extends('layouts.master')

@section('title', 'Owner Transfer Requests')

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <div class="card-header">
                <h5>Requests</h5>
                <div class="card-tools">
                    @can('mvr_initiate_transfer')
                        <button class="btn btn-info btn-sm"
                                onclick="Livewire.emit('showModal', 'mvr.chassis-number-internal-search','mvr.internal-search-ot')"><i
                                    class="bi bi-plus-circle-fill"></i>
                            New Requests</button>
                    @endcan
                </div>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="to-print-link" data-toggle="tab" href="#to-print" role="tab"
                       aria-controls="home" aria-selected="true">All Requests</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="printed-link" data-toggle="tab" href="#printed" role="tab"
                       aria-controls="profile" aria-selected="false">Pending Approval</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane p-2 show active" id="to-print" role="tabpanel" aria-labelledby="to-print-tab">
                    <livewire:mvr.ownership-transfer-requests-table :status="null"/>
                </div>
                <div class="tab-pane p-2" id="printed" role="tabpanel" aria-labelledby="printed-tab">
                    <livewire:mvr.ownership-transfer-requests-table :status="App\Models\MvrRequestStatus::STATUS_RC_PENDING_APPROVAL"/>
                </div>
            </div>

        </div>
    </div>
@endsection

