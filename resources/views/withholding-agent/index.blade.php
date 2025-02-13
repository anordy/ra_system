@extends('layouts.master')

@section('title')
    Withholding Agents
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                       aria-controls="home" aria-selected="true">Pending Requests</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab"
                       aria-controls="profile" aria-selected="false">Rejected Requests</a>
                </li>
            </ul>

            <div class="tab-content card" id="myTabContent">

                <div class="tab-pane p-4 show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <livewire:withholding-agents.withholding-agents-requests-table/>

                </div>
                <div class="tab-pane p-4" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                    <livewire:withholding-agents.rejected-withholding-agents-table/>

                </div>

            </div>
        </div>
    </div>
@endsection
