@extends('layouts.master')

@section('title')
    Debt Waiver Management
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Applications of Debt Waiver for Penalty & Interest
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#pending-approval" class="nav-item nav-link font-weight-bold active">Pending Approval</a>
                <a href="#all-businesses" class="nav-item nav-link font-weight-bold">All Waivers</a>
            </nav>

            <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                <div id="pending-approval" class="tab-pane fade active show m-2">
                    <livewire:debt.waiver.debt-waiver-approval-table />
                </div>
                <div id="all-businesses" class="tab-pane fade m-2">
                    <livewire:debt.waiver.debt-waiver-table />
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
