@extends('layouts.master')

@section('title', 'Transport Service De-registrations')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            Transport Service De-registrations
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab">
                <li class="nav-item">
                    <a class="nav-link active" id="to-print-link" data-toggle="tab" href="#all" role="tab"
                       aria-controls="home" aria-selected="true">All Registrations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="printed-link" data-toggle="tab" href="#pending-approval" role="tab"
                       aria-controls="profile" aria-selected="false">Pending Approval</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="printed-link" data-toggle="tab" href="#rejected" role="tab"
                       aria-controls="rejected" aria-selected="false">Rejected</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane p-2 show active" id="all" role="tabpanel" aria-labelledby="to-print-tab">
                    @livewire('public-service.de-registration.de-registrations-table')
                </div>
                <div class="tab-pane p-2" id="pending-approval" role="tabpanel" aria-labelledby="printed-tab">
                    @livewire('public-service.de-registration.de-registrations-table', ['status' => \App\Enum\PublicService\TemporaryClosureStatus::PENDING])
                </div>
                <div class="tab-pane p-2" id="rejected" role="tabpanel" aria-labelledby="printed-tab">
                    @livewire('public-service.de-registration.de-registrations-table', ['status' => \App\Enum\PublicService\TemporaryClosureStatus::REJECTED])
                </div>
            </div>
        </div>
    </div>
@endsection

