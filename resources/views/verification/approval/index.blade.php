@extends('layouts.master')

@section('title', 'Return Verification')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Returns Verification Approval
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#all-businesses" class="nav-item nav-link font-weight-bold active">Paid Returns</a>
                <a href="#pending-approval" class="nav-item nav-link font-weight-bold">UnPaid Returns</a>
            </nav>

            <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                <div id="all-businesses" class="tab-pane fade active show card p-2">
                    @livewire('verification.verification-approval-table', ['payment' => 'paid'])
                </div>
                <div id="pending-approval" class="tab-pane fade card p-2">
                    @livewire('verification.verification-approval-table', ['payment' => 'unpaid'])
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

