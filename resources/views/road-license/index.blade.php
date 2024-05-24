@extends('layouts.master')

@section('title', 'List Road License')

@section('content')

    <div class="card mt-3">
        <div class="card-header">
            Road License
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="to-print-link" data-toggle="tab" href="#all" role="tab"
                       aria-controls="home" aria-selected="true">All Road Licenses</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="printed-link" data-toggle="tab" href="#pending-approval" role="tab"
                       aria-controls="profile" aria-selected="false">Pending Approval</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane p-2 show active" id="all" role="tabpanel" aria-labelledby="to-print-tab">
                    @livewire('road-license.approved-requests-table')
                </div>
                @can('mvr_approve_registration')
                    <div class="tab-pane p-2" id="pending-approval" role="tabpanel" aria-labelledby="printed-tab">
                        @livewire('road-license.pending-requests-table')
                    </div>
                @endcan
            </div>

        </div>
    </div>

@endsection