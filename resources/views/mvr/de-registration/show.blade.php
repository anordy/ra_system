@extends('layouts.master')

@section('title', 'Motor Vehicle De-registration Details')

@section('content')

    @if($mvrDeregistration->status === \App\Enum\MvrRegistrationStatus::STATUS_PENDING_PAYMENT
        || $mvrDeregistration->status === \App\Enum\MvrRegistrationStatus::APPROVED)
        @livewire('mvr.fee-payment', ['motorVehicle' => $mvrDeregistration])
    @endif

    <ul class="nav nav-tabs shadow-sm mb-0" id="myTab">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" aria-controls="home" role="tab"
               aria-selected="true">
                De-Registration Information
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="approval-tab" data-toggle="tab" href="#approval" aria-controls="approval"
               role="tab" aria-selected="true">
                Approval History
            </a>
        </li>
    </ul>
    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade p-3 show active" id="home" role="tabpanel" aria-labelledby="home-tab">

            @include('mvr.de-registration.dereg_info', ['reg' => $mvrDeregistration])
            @include('mvr.de-registration.reg_info', ['reg' => $mvrDeregistration->registration])

            <livewire:approval.mvr.de-registration-approval-processing modelName='App\Models\MvrDeregistration'
                                                                    modelId="{{ encrypt($mvrDeregistration->id) }}" />
        </div>
        <div class="tab-pane fade p-3" id="approval" role="tabpanel" aria-labelledby="approval-tab">
            <livewire:approval.approval-history-table modelName='App\Models\MvrDeregistration'
                                                      modelId="{{ encrypt($mvrDeregistration->id) }}" />
        </div>
    </div>

@endsection