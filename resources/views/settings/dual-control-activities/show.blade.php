@extends('layouts.master')

@section('title')
    Dual Control Approval
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Activities for dual control</h5>
        </div>

        <div class="card-body">
            <div class="card">
                <div class="card-header">
                    Request Details
                </div>
                <div class="card-body">
                    <div class="row m-2 pt-3">
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Affected Module</span>
                            <p class="my-1">{{ (new \App\Models\DualControl())->moduleForBlade($result->controllable_type) }}</p>
                        </div>

                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Action Type</span>
                            <p class="my-1">{{ ucwords($result->action_detail) }}</p>
                        </div>

                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Created At</span>
                            <p class="my-1">{{ $result->created_at }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Status</span>
                            <p class="my-1">{{ ucwords($result->status) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @if ($result->action_detail === 'editing user role')
                @include('settings.dual-control-activities.details.user-role')
            @elseif ($result->controllable_type === \App\Models\DualControl::USER)
                @include('settings.dual-control-activities.details.user')
            @elseif ($result->controllable_type === \App\Models\DualControl::ROLE)
                @include('settings.dual-control-activities.details.roles')
            @elseif ($result->controllable_type === \App\Models\DualControl::SYSTEM_SETTING_CONFIG)
                @include('settings.dual-control-activities.details.system-settings')
            @elseif ($result->controllable_type === \App\Models\DualControl::SYSTEM_SETTING_CATEGORY)
                @include('settings.dual-control-activities.details.system-settings-category')
            @elseif ($result->controllable_type === \App\Models\DualControl::INTEREST_RATE)
                @include('settings.dual-control-activities.details.interest-rate')
            @elseif ($result->controllable_type === \App\Models\DualControl::PENALTY_RATE)
                @include('settings.dual-control-activities.details.penalty-rate')
            @elseif ($result->controllable_type === \App\Models\DualControl::CONSULTANT_FEE)
                @include('settings.dual-control-activities.details.consultant-fee')
            @elseif ($result->controllable_type === \App\Models\DualControl::FINANCIAL_YEAR)
                @include('settings.dual-control-activities.details.financial-year')
            @elseif ($result->controllable_type === \App\Models\DualControl::FINANCIAL_MONTH || $result->controllable_type === \App\Models\DualControl::SEVEN_FINANCIAL_MONTH)
                @include('settings.dual-control-activities.details.financial-month')
            @elseif ($result->controllable_type === \App\Models\DualControl::COUNTRY)
                @include('settings.dual-control-activities.details.country')
            @elseif ($result->controllable_type === \App\Models\DualControl::REGION)
                @include('settings.dual-control-activities.details.region')
            @elseif ($result->controllable_type === \App\Models\DualControl::DISTRICT)
                @include('settings.dual-control-activities.details.district')
            @elseif ($result->controllable_type === \App\Models\DualControl::WARD)
                @include('settings.dual-control-activities.details.ward')
            @elseif ($result->controllable_type === \App\Models\DualControl::EXCHANGE_RATE)
                @include('settings.dual-control-activities.details.exchange-rate')
            @endif


            <div class="d-flex justify-content-end">
                @if(approvalLevel(Auth::user()->level_id, 'Checker'))
                    @if($result->status == 'pending')
                    <livewire:settings.dual-control-activity.approve dual_control_id="{{encrypt($result->id)}}"/>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
