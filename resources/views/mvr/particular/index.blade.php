@extends('layouts.master')

@section('title', 'List Motor Vehicle Registration Particular Change')

@section('content')

    <div class="card mt-3">
        <div class="card-header">
            <h5>Motor Vehicles</h5>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="to-print-link" data-toggle="tab" href="#all" role="tab"
                       aria-controls="home" aria-selected="true">All Particulars change</a>
                </li>
                @can('mvr_approve_registration')
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="printed-link" data-toggle="tab" href="#pending-approval" role="tab"
                           aria-controls="profile" aria-selected="false">Pending Approval</a>
                    </li>
                @endcan
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane p-2 show active" id="all" role="tabpanel" aria-labelledby="to-print-tab">
                    <livewire:mvr.particular.mvr-approved-registrations-particular-change-table />
                </div>
                @can('motor-vehicle-status-change-request')
                    <div class="tab-pane p-2" id="pending-approval" role="tabpanel" aria-labelledby="printed-tab">
                        <livewire:mvr.particular.mvr-registrations-particular-change-table/>
                    </div>
                @endcan
            </div>

        </div>
    </div>

@endsection