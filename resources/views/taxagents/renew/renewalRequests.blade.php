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
                    <a class="nav-link" id="academic-tab" data-toggle="tab" href="#academic" role="tab"
                       aria-controls="profile" aria-selected="false">Verified Renew Requests</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab"
                       aria-controls="profile" aria-selected="false">Approved Renew Requests</a>
                </li>
            </ul>

            <div class="tab-content card" id="myTabContent">

                <div class="tab-pane p-2 show active" id="biz" role="tabpanel" aria-labelledby="biz-tab">
                    <div class="text-center pb-2">
                        Requests needed for verification
                    </div>
                    <livewire:tax-agent.renew.pending-table />
                </div>
                <div class="tab-pane p-2" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                    <div class="text-center pb-2">
                        Requests needed for approval
                    </div>
                    <livewire:tax-agent.renew.verified-table />
                </div>

                <div class="tab-pane p-2" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                    <div class="text-center pb-2">
                        Approved Requests
                    </div>
                    <livewire:tax-agent.renew.approved-table />
                </div>

            </div>
        </div>
    </div>
@endsection