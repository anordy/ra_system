@extends('layouts.master')

@section('title', 'Show Motor Vehicle Status Change')

@section('content')

    @if($change_req->status === \App\Enum\MvrRegistrationStatus::STATUS_PENDING_PAYMENT || $change_req->status === \App\Enum\MvrRegistrationStatus::STATUS_REGISTERED
|| $change_req->status === \App\Enum\MvrRegistrationStatus::STATUS_PLATE_NUMBER_PRINTING)
        @livewire('mvr.fee-payment', ['motorVehicle' => $change_req])
    @endif

    <ul class="nav nav-tabs shadow-sm mb-0" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" aria-controls="home" role="tab"
               aria-selected="true">
                Registration Information
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="approval-tab" data-toggle="tab" href="#approval" aria-controls="approval"
               role="tab" aria-selected="true">
                Approval History
            </a>
        </li>
    </ul>
    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade p-3 show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            @include('mvr.status.current_change_info', ['reg' => $motorVehicle])
            @include('mvr.status.new_change_info', ['reg' => $change_req])
            <livewire:approval.mvr.status-approval-processing modelName='App\Models\MvrRegistrationStatusChange'
                                                      modelId="{{ encrypt($change_req->id) }}" />
        </div>
        <div class="tab-pane fade p-3" id="approval" role="tabpanel" aria-labelledby="approval-tab">
            <livewire:approval.approval-history-table modelName='App\Models\MvrRegistrationStatusChange'
                                                      modelId="{{ encrypt($change_req->id) }}" />
        </div>
    </div>

@endsection