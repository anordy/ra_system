@extends('layouts.master')

@section('title', 'Motor Vehicle Registrations')

@section('content')

    <div class="card mt-3">
        <div class="card-header">Motor Vehicle Registrations</div>

        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab">
                <li class="nav-item">
                    <a class="nav-link active" id="to-print-link" data-toggle="tab" href="#all" role="tab"
                       aria-controls="home" aria-selected="true">Approved Motor Vehicles</a>
                </li>
                @can('mvr_approve_registration')
                    <li class="nav-item">
                        <a class="nav-link" id="printed-link" data-toggle="tab" href="#pending-approval" role="tab"
                           aria-controls="profile" aria-selected="false">Pending Approval</a>
                    </li>
                @endcan
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane p-2 show active" id="all" role="tabpanel" aria-labelledby="to-print-tab">
                    <livewire:mvr.registration.mvr-approved-registrations-table />
                </div>
                @can('mvr_approve_registration')
                    <div class="tab-pane p-2" id="pending-approval" role="tabpanel" aria-labelledby="printed-tab">
                        <livewire:mvr.registration.mvr-registrations-table/>
                    </div>
                @endcan
            </div>

        </div>
    </div>

@endsection