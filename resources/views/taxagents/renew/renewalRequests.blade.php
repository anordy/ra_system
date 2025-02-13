@extends('layouts.master')

@section('title', 'Tax Consultants')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            Tax Consultant Renew Request
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                       aria-controls="home" aria-selected="true">Pending Renew Request</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="progress-tab" data-toggle="tab" href="#progress" role="tab"
                       aria-controls="progress" aria-selected="true">Progress Renew Request</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab"
                       aria-controls="profile" aria-selected="false">Approved Renew Requests</a>
                </li>
            </ul>

            <div class="tab-content card" id="myTabContent">

                <div class="tab-pane p-2 show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <livewire:tax-agent.renew.pending-table />
                </div>
                <div class="tab-pane p-2" id="progress" role="tabpanel" aria-labelledby="progress-tab">
                    <livewire:tax-agent.renew.progress-table />
                </div>
                <div class="tab-pane p-2" id="approved" role="tabpanel" aria-labelledby="approved-tab">

                    <livewire:tax-agent.renew.approved-table />
                </div>

            </div>
        </div>
    </div>
@endsection