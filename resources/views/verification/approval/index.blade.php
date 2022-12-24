@extends('layouts.master')

@section('title', 'Return Verification')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Returns Verification Approval
        </div>
        <div class="card-body">
            @livewire('approval.approval-count-card', ['modelName' => 'TaxVerification'])
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#all-businesses" class="nav-item nav-link font-weight-bold active">Pending Approval</a>
                <a href="#approval-progress" class="nav-item nav-link font-weight-bold">Approval Progress</a>
                <a href="#pending-approval" class="nav-item nav-link font-weight-bold">Unpaid Returns</a>
            </nav>

            <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                <div id="all-businesses" class="tab-pane fade active show p-2">
                    <div class="card p-2">
                        @livewire('returns.verification-filter', ['tablename' => $paidAproval])
                    </div>
                    <div class="card p-2">
                        @livewire('verification.verification-approval-table')
                    </div>
                </div>
                <div id="approval-progress" class="tab-pane fade p-2">
                    @livewire('verification.verification-approval-progress-table')
                </div>
                <div id="pending-approval" class="tab-pane fade p-2">
                    <div class="card p-2">
                        @livewire('returns.verification-filter', ['tablename' => $unPaidAproval])
                    </div>
                    <div class="card p-2">
                        @livewire('verification.verification-unpaid-approval-table')
                    </div>
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
