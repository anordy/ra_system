@extends('layouts.master')

@section('title','Installment Requests')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Installment Requests
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#all-requests" class="nav-item nav-link font-weight-bold active">All Installments</a>
                <a href="#pending-approval" class="nav-item nav-link font-weight-bold">Pending Approval</a>
                <a href="#approval-progress" class="nav-item nav-link font-weight-bold">Approval Progress</a>
                <a href="#rejected" class="nav-item nav-link font-weight-bold">Rejected</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2 border">
                <div id="all-requests" class="tab-pane fade active show p-2">
                    <livewire:installment.installment-requests-table />
                </div>
                <div id="pending-approval" class="tab-pane fade p-2">
                    <livewire:installment.installment-requests-approval-table />
                </div>
                <div id="approval-progress" class="tab-pane fade p-2">
                    <livewire:installment.installment-requests-approval-progress-table />
                </div>
                <div id="rejected" class="tab-pane fade p-2">
                    <livewire:installment.installment-requests-table rejected="true" />
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