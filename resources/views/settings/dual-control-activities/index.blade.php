@extends('layouts.master')

@section('title')
    Dual Control Activities
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Activities for dual control</h5>
        </div>

        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Pending Requests</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Approved Requests</a>
                <a href="#tab3" class="nav-item nav-link font-weight-bold">Reject Requests</a>
            </nav>
            <div class="tab-content px-2 border border-top-0 pt-3 pb-2">
                <div id="tab1" class="tab-pane fade active show p-2">
                    <livewire:settings.dual-control-activity.activity-table status="pending"/>
                </div>
                <div id="tab2" class="tab-pane fade p-2">
                    <livewire:settings.dual-control-activity.activity-table status="approved"/>
                </div>

                <div id="tab3" class="tab-pane fade p-2">
                    <livewire:settings.dual-control-activity.activity-table status="rejected"/>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $(".nav-tabs a").click(function () {
                $(this).tab('show');
            });
        });
    </script>
@endsection
