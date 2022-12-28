@extends('layouts.master')

@section('title')
    Temporary Business Closures
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Temporary Businesses Closures
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab2" class="nav-item nav-link font-weight-bold active">Pending Approvals</a>
                <a href="#tab5" class="nav-item nav-link font-weight-bold">On Progress Approvals</a>
                <a href="#tab1" class="nav-item nav-link font-weight-bold">Approved Closures</a>
                <a href="#tab3" class="nav-item nav-link font-weight-bold">Rejected Closures</a>
                <a href="#tab4" class="nav-item nav-link font-weight-bold">Reopened Closures</a>
            </nav>
            <div class="tab-content px-2 card pt-3 pb-2">
                <div id="tab2" class="tab-pane fade active show m-2">
                    @livewire('business.closure.pending-closures-table')
                </div>
                <div id="tab5" class="tab-pane fade show m-2">
                    @livewire('business.closure.closure-approval-progress-table')
                </div>
                <div id="tab1" class="tab-pane fade m-2">
                    @livewire('business.closure.approved-closures-table')
                </div>
                <div id="tab3" class="tab-pane fade m-2">
                    @livewire('business.closure.rejected-closures-table')
                </div>
                <div id="tab4" class="tab-pane fade m-2">
                    @livewire('business.closure.reopened-closures-table')
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
