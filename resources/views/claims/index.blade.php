@extends('layouts.master')

@section('title','Tax Claims')

@section('content')
    <div class="card">
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#all-claims" class="nav-item nav-link font-weight-bold active">All Claims</a>
                <a href="#pending-approval" class="nav-item nav-link font-weight-bold">Pending Approval</a>
                <a href="#rejected" class="nav-item nav-link font-weight-bold">Rejected</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2 border">
                <div id="all-claims" class="tab-pane fade active show  p-2">
                    <livewire:claims.claims-table />
                </div>
                <div id="pending-approval" class="tab-pane fade  p-2">
                    <livewire:claims.claims-approval-table />
                </div>
                <div id="rejected" class="tab-pane fade  p-2">
                    <livewire:claims.claims-table rejected="true" />
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