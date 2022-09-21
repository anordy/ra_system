@extends('layouts.master')

@section('title', 'Tax Consultants')

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#biz" role="tab"
                       aria-controls="home" aria-selected="true">Verification request</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="academic-tab" data-toggle="tab" href="#academic" role="tab"
                       aria-controls="profile" aria-selected="false">Approval Requests</a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab"
                       aria-controls="profile" aria-selected="false">Rejected Requests</a>
                </li>
            </ul>

            <div class="tab-content card" id="myTabContent">

                <div class="tab-pane p-4 show active" id="biz" role="tabpanel" aria-labelledby="biz-tab">
                    <livewire:tax-agent.verification-requests-table/>
                </div>
                <div class="tab-pane p-4" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                    <livewire:tax-agent.tax-agent-table/>
                </div>

                <div class="tab-pane p-4" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                    <livewire:tax-agent.rejected-tax-agent-table/>
                </div>

            </div>
        </div>
    </div>
@endsection