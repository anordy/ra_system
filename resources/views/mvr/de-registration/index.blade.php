@extends('layouts.master')

@section('title', 'Motor Vehicle De-registrations')

@section('content')

    <div class="card mt-3">
        <div class="card-header">Deregistered Motor Vehicles</div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab">
                <li class="nav-item">
                    <a class="nav-link active" id="to-print-link" data-toggle="tab" href="#all" role="tab"
                       aria-controls="home" aria-selected="true">All Motor Vehicles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="printed-link" data-toggle="tab" href="#pending-approval" role="tab"
                       aria-controls="profile" aria-selected="false">Pending Approval</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane p-2 show active" id="all" role="tabpanel" aria-labelledby="to-print-tab">
                    <livewire:mvr.deregistration.deregistrations-table></livewire:mvr.deregistration.deregistrations-table>
                </div>
                <div class="tab-pane p-2" id="pending-approval" role="tabpanel" aria-labelledby="printed-tab">
                    <livewire:mvr.deregistration.pending-deregistrations-table></livewire:mvr.deregistration.pending-deregistrations-table>
                </div>
            </div>

        </div>
    </div>

@endsection
