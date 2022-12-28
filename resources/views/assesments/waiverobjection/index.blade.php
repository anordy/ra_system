@extends('layouts.master')

@section('title')
    Waiver & Objection Management
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Application for Weaver & Objection
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0 mb-3">
                <a href="#paid-approval" class="nav-item nav-link font-weight-bold active show">Pending Approval</a>
                <a href="#progress-approval" class="nav-item nav-link font-weight-bold">Approval Progress</a>

                <a href="#approved-approval" class="nav-item nav-link font-weight-bold ">Approved</a>
                <a href="#rejected-approval" class="nav-item nav-link font-weight-bold">Rejected</a>
            </nav>

            <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                <div id="paid-approval" class="tab-pane fade  active show">
                    @livewire('assesments.dispute-approval-table', ['category' => 'waiver-and-objection'])
                </div>
                <div id="progress-approval" class="tab-pane fade">
                    @livewire('assesments.dispute-approval-progress-table',['category' => 'waiver-and-objection' ])
                </div>
                <div id="approved-approval" class="tab-pane fade">
                    @livewire('assesments.waiver.waiver-table', ['category' => 'waiver-and-objection', 'status' => 'approved'])
                </div>
                <div id="rejected-approval" class="tab-pane fade">
                    @livewire('assesments.waiver.waiver-table', ['category' => 'waiver-and-objection', 'status' => 'rejected'])
                </div>


            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".nav-tabs a").click(function() {
                $(this).tab('show');
            });
        });
    </script>
@endsection
