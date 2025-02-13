@extends('layouts.master')

@section('title', 'View Transport Service Registration')

@section('content')

    <ul class="nav nav-tabs shadow-sm" id="myTab" style="margin-bottom: 0;">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
               aria-selected="true"> Transport Service Registration Information</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="approval-tab" data-toggle="tab" href="#approval" role="tab" aria-controls="approval"
               aria-selected="false">Approval History</a>
        </li>
    </ul>

    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            @include('public-service.includes.info')
            @include('mvr.registration.reg_info', ['reg' => $registration->mvr])
            @include('mvr.registration.chassis_info', ['motor_vehicle' => $registration->mvr->chassis])
            @can('public-service-approve-registrations')
                <livewire:approval.mvr.public-service-registration-approval-processing
                        modelName="{{ get_class($registration) }}"
                        modelId="{{ encrypt($registration->id) }}" />
            @endcan
        </div>


        <div class="tab-pane fade m-2" id="approval" role="tabpanel" aria-labelledby="approval-tab">
            <livewire:approval.approval-history-table
                    modelName='{{ \App\Models\PublicService\PublicServiceMotor::class }}'
                    modelId="{{ encrypt($registration->id) }}"/>
        </div>
    </div>

@endsection
