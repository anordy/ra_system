@extends('layouts.master')

@section('title', 'Public Service Registrations')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            Public Service Registrations
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="to-print-link" data-toggle="tab" href="#all" role="tab"
                       aria-controls="home" aria-selected="true">All Registrations</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="printed-link" data-toggle="tab" href="#pending-approval" role="tab"
                       aria-controls="profile" aria-selected="false">Pending Approval</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane p-2 show active" id="all" role="tabpanel" aria-labelledby="to-print-tab">
                    @livewire('public-service.registration.registration-table', ['status' => \App\Enum\PublicServiceMotorStatus::REGISTERED])
                </div>
                <div class="tab-pane p-2" id="pending-approval" role="tabpanel" aria-labelledby="printed-tab">
                    @livewire('public-service.registration.registration-table', ['status' => \App\Enum\PublicServiceMotorStatus::PENDING])
                </div>
            </div>

        </div>
    </div>
@endsection

