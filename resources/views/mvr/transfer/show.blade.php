@extends('layouts.master')

@section('title', 'Show Motor Vehicle Ownership Transfer')

@section('content')

    @if($request->status === \App\Enum\MvrRegistrationStatus::STATUS_PENDING_PAYMENT || $request->status === \App\Enum\MvrRegistrationStatus::STATUS_REGISTERED || $request->status === \App\Models\MvrRequestStatus::STATUS_RC_ACCEPTED)
        @livewire('mvr.payment.fee-payment', ['motorVehicle' => $request])
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
            @include('mvr.transfer.mvr_info', ['reg' => $motor_vehicle, 'request' => $request])
            @include('mvr.transfer.owner_info', ['taxPayer' => $previousOwner,'owner'=>'previous'])
            @include('mvr.transfer.owner_info', ['taxPayer' => $newOwner,'owner'=>'new'])
            <livewire:approval.mvr.transfer-approval-processing modelName='App\Models\MvrOwnershipTransfer'
                                                      modelId="{{ encrypt($request->id) }}" />
        </div>
        <div class="tab-pane fade p-3" id="approval" role="tabpanel" aria-labelledby="approval-tab">
            <livewire:approval.approval-history-table modelName='App\Models\MvrOwnershipTransfer'
                                                      modelId="{{ encrypt($request->id) }}" />
        </div>
    </div>

@endsection