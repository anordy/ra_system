@extends('layouts.master')
@section('title', 'Motor Vehicle Temporary Transportation')
@section('content')
    <div class="card p-0 m-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <div class="text-uppercase font-weight-bold">{{ __('Motor Vehicle Temporary Transportation') }}</div>
        </div>
        <div class="card-body mt-0 p-2">
            <ul class="nav nav-tabs" id="myTab">
                <li class="nav-item">
                    <a class="nav-link active" id="to-print-link" data-toggle="tab" href="#to-print" role="tab"
                       aria-controls="home" aria-selected="true">Approved</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="printed-link" data-toggle="tab" href="#pending" role="tab"
                       aria-controls="profile" aria-selected="false">Pending Approval</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="printed-link" data-toggle="tab" href="#rejected" role="tab"
                       aria-controls="profile" aria-selected="false">Rejected</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="printed-link" data-toggle="tab" href="#returned" role="tab"
                       aria-controls="profile" aria-selected="false">Returned</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="printed-link" data-toggle="tab" href="#correction" role="tab"
                       aria-controls="profile" aria-selected="false">Pending Correction</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane p-2 show active" id="to-print" role="tabpanel" aria-labelledby="to-print-tab">
                    <livewire:mvr.temporary-transport.temporary-transports-table :status="App\Enum\MvrTemporaryTransportStatus::APPROVED"/>
                </div>
                <div class="tab-pane p-2" id="pending" role="tabpanel" aria-labelledby="printed-tab">
                    <livewire:mvr.temporary-transport.temporary-transports-table :status="App\Enum\MvrTemporaryTransportStatus::PENDING"/>
                </div>
                <div class="tab-pane p-2" id="rejected" role="tabpanel" aria-labelledby="printed-tab">
                    <livewire:mvr.temporary-transport.temporary-transports-table :status="App\Enum\MvrTemporaryTransportStatus::REJECTED"/>
                </div>
                <div class="tab-pane p-2" id="returned" role="tabpanel" aria-labelledby="printed-tab">
                    <livewire:mvr.temporary-transport.temporary-transports-table :status="App\Enum\MvrTemporaryTransportStatus::RETURNED"/>
                </div>
                <div class="tab-pane p-2" id="correction" role="tabpanel" aria-labelledby="printed-tab">
                    <livewire:mvr.temporary-transport.temporary-transports-table :status="App\Enum\MvrTemporaryTransportStatus::CORRECTION"/>
                </div>
            </div>
        </div>
    </div>
@endsection