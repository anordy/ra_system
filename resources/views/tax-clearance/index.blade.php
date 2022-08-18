@extends('layouts.master')

@section('title')
    Tax Clearence
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax CLearence Request
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0 mb-3">
                <a href="#all-approval" class="nav-item nav-link font-weight-bold active">All Clearence</a>
                <a href="#approved-approval" class="nav-item nav-link font-weight-bold">Approved Clearence</a>
                <a href="#rejected-approval" class="nav-item nav-link font-weight-bold">Rejected Clearence</a>
            </nav>

            <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                <div id="all-approval" class="tab-pane fade active show">
                    <livewire:tax-clearance.tax-clearance-request-approval-table />
                </div>
                <div id="approved-approval" class="tab-pane fade">
                    @livewire('tax-clearance.tax-clearance-request-table', ['status' => App\Enum\TaxClearanceStatus::APPROVED])
                </div>
                <div id="rejected-approval" class="tab-pane fade">
                    @livewire('tax-clearance.tax-clearance-request-table', ['status' => App\Enum\TaxClearanceStatus::REJECTED])
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
