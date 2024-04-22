@extends('layouts.master')

@section('title', 'Driver\'s License')

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            License Applications
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Completed Applications</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Pending Approval</a>
                <a href="#tab3" class="nav-item nav-link font-weight-bold">Pending Payment</a>
                <a href="#tab4" class="nav-item nav-link font-weight-bold">Pending Taking Picture</a>
                <a href="#tab5" class="nav-item nav-link font-weight-bold">Pending License Printing</a>
            </nav>
            <div class="tab-content px-2 card pt-3 pb-2">
                <div id="tab1" class="tab-pane fade active show m-2">
                    <livewire:drivers-license.license-applications-table :status="App\Models\DlApplicationStatus::STATUS_COMPLETED" />
                </div>
                <div id="tab2" class="tab-pane fade m-2">
                    <livewire:drivers-license.license-applications-table :status="App\Models\DlApplicationStatus::STATUS_INITIATED" />
                </div>
                <div id="tab3" class="tab-pane fade m-2">
                    <livewire:drivers-license.license-applications-table :status="App\Models\DlApplicationStatus::STATUS_PENDING_PAYMENT" />
                </div>
                <div id="tab4" class="tab-pane fade m-2">
                    <livewire:drivers-license.license-applications-table :status="App\Models\DlApplicationStatus::STATUS_TAKING_PICTURE" />
                </div>
                <div id="tab5" class="tab-pane fade m-2">
                    <livewire:drivers-license.license-applications-table :status="App\Models\DlApplicationStatus::STATUS_LICENSE_PRINTING" />
                </div>
            </div>
        </div>
    </div>
@endsection
