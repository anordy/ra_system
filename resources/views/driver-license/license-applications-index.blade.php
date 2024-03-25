@extends('layouts.master')

@section('title', 'Driver\'s License')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h5>Applications</h5>
            <div class="card-tools">
            </div>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="to-print-link" data-toggle="tab" href="#all" role="tab"
                        aria-controls="home" aria-selected="true">All Applications</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="printed-link" data-toggle="tab" href="#pending-approval" role="tab"
                        aria-controls="profile" aria-selected="false">Pending Approval</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="printed-link" data-toggle="tab" href="#pending-approval" role="tab"
                        aria-controls="profile" aria-selected="false">Pending Payments</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="printed-link" data-toggle="tab" href="#pending-approval" role="tab"
                        aria-controls="profile" aria-selected="false">Pending Printing</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="printed-link" data-toggle="tab" href="#completed" role="tab"
                        aria-controls="profile" aria-selected="false">Completed Applications</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane p-2 show active" id="all" role="tabpanel" aria-labelledby="to-print-tab">
                    <livewire:drivers-license.license-applications-table :status="null" />
                </div>

                <div class="tab-pane p-2" id="pending-approval" role="tabpanel" aria-labelledby="printed-tab">
                    <livewire:drivers-license.license-applications-table :status="App\Models\DlApplicationStatus::STATUS_PENDING_APPROVAL" />
                </div>
                <div class="tab-pane p-2" id="pending-approval" role="tabpanel" aria-labelledby="printed-tab">
                    <livewire:drivers-license.license-applications-table :status="App\Models\DlApplicationStatus::STATUS_PENDING_APPROVAL" />
                </div>
                <div class="tab-pane p-2" id="pending-approval" role="tabpanel" aria-labelledby="printed-tab">
                    <livewire:drivers-license.license-applications-table :status="App\Models\DlApplicationStatus::STATUS_PENDING_APPROVAL" />
                </div>
                <div class="tab-pane p-2" id="pending-approval" role="tabpanel" aria-labelledby="printed-tab">
                    <livewire:drivers-license.license-applications-table :status="App\Models\DlApplicationStatus::STATUS_COMPLETED" />
                </div>
            </div>

        </div>
    </div>
@endsection
