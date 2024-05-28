@extends('layouts.master')
@section('title', 'Motor Vehicle Temporary Transportation')
@section('content')
    <div class="card p-0 m-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <div class="text-uppercase font-weight-bold">{{ __('Motor Vehicle Temporary Transportation') }}</div>
        </div>
        <div class="card-body mt-0 p-2">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="to-print-link" data-toggle="tab" href="#to-print" role="tab"
                       aria-controls="home" aria-selected="true">All Requests</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="printed-link" data-toggle="tab" href="#printed" role="tab"
                       aria-controls="profile" aria-selected="false">Pending Approval</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane p-2 show active" id="to-print" role="tabpanel" aria-labelledby="to-print-tab">
                    <livewire:mvr.temporary-transport.temporary-transports-table :status="null"/>
                </div>
                <div class="tab-pane p-2" id="printed" role="tabpanel" aria-labelledby="printed-tab">
                    <livewire:mvr.temporary-transport.temporary-transports-table :status="App\Enum\MvrTemporaryTransportStatus::PENDING"/>
                </div>
            </div>
        </div>
    </div>
@endsection