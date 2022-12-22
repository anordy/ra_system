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
            @include('settings.dual-control-activities.details.user')

            <div class="d-flex justify-content-end">
                <livewire:settings.dual-control-activity.approve dual_control_id="{{encrypt($result->id)}}"/>

            </div>
        </div>
    </div>
@endsection
