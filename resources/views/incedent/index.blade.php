@extends('layouts.master')

@section('title', 'Revenue Leakage')

@section('content')

    <div class="card mt-3">
        <div class="card-header">Revenue Assurance Incedent</div>
        <div class="card-tools">
            {{-- @if(approvalLevel(Auth::user()->level_id, 'Maker')) --}}
            <a class="btn btn-primary text-capitalize" href="{{ route('ra.incedent.create') }}">
                <i class="bi bi-stop-circle mr-1"></i>
                Create Incedent
            </a>
            {{-- @endif --}}
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab">
                <li class="nav-item">
                    <a class="nav-link active" id="to-print-link" data-toggle="tab" href="#all" role="tab"
                       aria-controls="home" aria-selected="true">Approved Revenue Incedent</a>
                </li>
                    <li class="nav-item">
                        <a class="nav-link" id="printed-link" data-toggle="tab" href="#pending-approval" role="tab"
                           aria-controls="profile" aria-selected="false">Pending Approval</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="printed-link" data-toggle="tab" href="#rejected-approval" role="tab"
                           aria-controls="profile" aria-selected="false">Rejected Approval</a>
                    </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane p-2 show active" id="all" role="tabpanel" aria-labelledby="to-print-tab">
                    <livewire:incedent.incedent-table />
                </div>
                    <div class="tab-pane p-2" id="pending-approval" role="tabpanel" aria-labelledby="printed-tab">
                        <livewire:incedent.incedent-pending-table />
                    </div>
                    <div class="tab-pane p-2" id="rejected-approval" role="tabpanel" aria-labelledby="printed-tab">
                        <livewire:incedent.incedent-rejected-table />
                    </div>
            </div>

        </div>
    </div>

@endsection