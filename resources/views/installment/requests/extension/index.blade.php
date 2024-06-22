@extends('layouts.master')

@section('title','Installment Extension Requests')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Installment Requests
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#all-requests" class="nav-item nav-link font-weight-bold active">All Installments Extension</a>
                <a href="#pending-approval" class="nav-item nav-link font-weight-bold">Pending Approval</a>
                <a href="#partial-installment" class="nav-item nav-link font-weight-bold">Partial Installment</a>
                <a href="#rejected" class="nav-item nav-link font-weight-bold">Rejected</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2 border">
                <div id="all-requests" class="tab-pane fade active show p-2">
                    <livewire:installment.installment-extension-request-table />
                </div>
                <div id="pending-approval" class="tab-pane fade p-2">
                    <livewire:installment.installment-extension-request-approval-table />
                </div>
                <div id="partial-installment" class="tab-pane fade p-2">
                    <livewire:installment.partial-payment-request />
                </div>
                <div id="rejected" class="tab-pane fade p-2">
                    <livewire:installment.installment-extension-request-table rejected="true" />
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