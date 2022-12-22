@extends('layouts.master')

@section('title', 'Tax Consultants')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            Tax Consultant Renew Request
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#biz" role="tab"
                       aria-controls="home" aria-selected="true">Pending Renew Request</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab"
                       aria-controls="profile" aria-selected="false">Approved Renew Requests</a>
                </li>
            </ul>

            <div class="tab-content card" id="myTabContent">

                <div class="tab-pane p-2 show active" id="biz" role="tabpanel" aria-labelledby="biz-tab">

                    <livewire:tax-agent.renew.pending-table />
                </div>
                <div class="tab-pane p-2" id="approved" role="tabpanel" aria-labelledby="approved-tab">

                    <livewire:tax-agent.renew.approved-table />
                </div>

            </div>
        </div>
    </div>
@endsection