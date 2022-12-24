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
            @if ($result->controllable_type === \App\Models\DualControl::USER)
                @include('settings.dual-control-activities.details.user')
            @elseif ($result->controllable_type === \App\Models\DualControl::SYSTEMSETTINGCONFIG)
                @include('settings.dual-control-activities.details.system-settings')
            @elseif ($result->controllable_type === \App\Models\DualControl::SYSTEMSETTINGCATEGORY)
                @include('settings.dual-control-activities.details.system-settings-category')
            @endif

            <div class="d-flex justify-content-end">
                @if(approvalLevel(Auth::user()->level_id, 'Checker'))
                    <livewire:settings.dual-control-activity.approve dual_control_id="{{encrypt($result->id)}}"/>
                @endif
            </div>
        </div>
    </div>
@endsection
