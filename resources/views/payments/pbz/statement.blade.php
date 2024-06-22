@extends('layouts.master')

@section('title')
    PBZ Statement - {{ $statement->stmdt->toFormattedDateString() }}
@endsection

@section('content')
    <nav class="nav nav-tabs mt-0 border-top-0">
        <a href="#tab1" class="nav-item nav-link font-weight-bold active">Statement Details</a>
        <a href="#tab2" class="nav-item nav-link font-weight-bold">Payments</a>
        <a href="#tab3" class="nav-item nav-link font-weight-bold">Reversals</a>
    </nav>
    <div class="tab-content card p-4">
        <div id="tab1" class="tab-pane fade active show m-4">
            <div class="row ">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Status</span>
                    <p class="my-1 text-uppercase">@include('payments.pbz.includes.statement-status', ['row' => $statement])</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Account Name</span>
                    <p class="my-1 text-uppercase">{{ $statement->account_name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Account Number</span>
                    <p class="my-1 text-uppercase">{{ $statement->account_no ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Currency</span>
                    <p class="my-1 text-uppercase">{{ $statement->currency ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Statement Date</span>
                    <p class="my-1 text-uppercase">{{ $statement->stmdt ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Generated At</span>
                    <p class="my-1 text-uppercase">{{ $statement->credttm ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Nature of opening Balance</span>
                    <p class="my-1 text-uppercase">{{ $statement->opencdtdbtind ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Opening Balance</span>
                    <p class="my-1 text-uppercase">{{ $statement->openbal ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Nature of closing Balance</span>
                    <p class="my-1 text-uppercase">{{ $statement->closecdtdbtind ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Closing Balance</span>
                    <p class="my-1 text-uppercase">{{ $statement->closebal ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">No. Of Transactions</span>
                    <p class="my-1 text-uppercase">{{ $statement->nboftxs ?? 'N/A' }}</p>
                </div>

                @if($statement->status == \App\Enum\StatementStatus::FAILED_SUBMISSION)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Status Description</span>
                        <p class="my-1 text-uppercase">{{ $statement->error ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-12 text-right">
                        <livewire:payments.p-b-z-statement-request :statement="$statement"></livewire:payments.p-b-z-statement-request>
                    </div>
                @endif
            </div>
        </div>
        <div id="tab2" class="tab-pane fade m-4">
            <livewire:payments.p-b-z-statement-export statementId='{{ $statement->id }}' exportType="{{ \App\Models\PBZTransaction::class }}"></livewire:payments.p-b-z-statement-export>
            <livewire:payments.p-b-z-payments-table statement='{{ $statement->id }}'></livewire:payments.p-b-z-payments-table>
        </div>
        <div id="tab3" class="tab-pane fade m-4">
            <livewire:payments.p-b-z-statement-export statementId='{{ $statement->id }}' exportType="{{ \App\Models\PBZReversal::class }}"></livewire:payments.p-b-z-statement-export>
            <livewire:payments.p-b-z-reversals-table statement='{{ $statement->id }}'></livewire:payments.p-b-z-reversals-table>
        </div>
    </div>
@endsection