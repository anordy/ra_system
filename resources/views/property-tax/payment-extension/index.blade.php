@extends('layouts.master')

@section('title','Registered Condominium Properties')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Property Payment Extension Requests</h5>
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Approved</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Pending Approval</a>
                <a href="#tab3" class="nav-item nav-link font-weight-bold">Rejected Approval</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2">
                <div id="tab1" class="tab-pane fade m-2 show active">
                    @livewire('property-tax.payment-extension.payment-extension-table', ['status' => \App\Enum\PaymentExtensionStatus::APPROVED])
                </div>
                <div id="tab2" class="tab-pane fade m-2">
                    @livewire('property-tax.payment-extension.payment-extension-table', ['status' => \App\Enum\PaymentExtensionStatus::PENDING])
                </div>
                <div id="tab3" class="tab-pane fade m-2">
                    @livewire('property-tax.payment-extension.payment-extension-table', ['status' => \App\Enum\PaymentExtensionStatus::REJECTED])
                </div>
            </div>
        </div>
        <div class="card-body">
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