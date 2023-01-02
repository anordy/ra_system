@extends('layouts.master')

@section('title', 'Bank Reconciliations Details')

@section('content')
    <div class="card rounded-0">
        <div class="card-header font-weight-bold text-uppercase bg-white">
            Reconciliation Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Reconciliation Status</span>
                    <p class="my-1">
                        @include('payments.includes.bank-recon-status', ['row' => $recon])
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Control No.</span>
                    <p class="my-1">{{ $recon->control_no }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Transaction Date</span>
                    <p class="my-1">{{ $recon->transaction_date->toFormattedDateString() }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Transaction Type</span>
                    <p class="my-1">{{ $recon->transaction_type }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Payment Ref</span>
                    <p class="my-1">{{ $recon->payment_ref }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Paid Amount</span>
                    <p class="my-1">{{ number_format($recon->credit_amount, 2) }} TZS</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Payer Name</span>
                    <p class="my-1">{{ $recon->payer_name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Transaction Origin</span>
                    <p class="my-1">{{ $recon->transaction_origin ?: 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card rounded-0">
        <div class="card-header font-weight-bold text-uppercase bg-white">
            Bill Details
        </div>
        <div class="card-body">
            @if($recon->bill)
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Bill Amount</span>
                        <p class="my-1">{{ number_format($recon->bill->amount, 2) }} {{ $recon->bill->currency }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Bill Status</span>
                        <p class="my-1 text-uppercase">{{ $recon->bill->status }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Control No.</span>
                        <p class="my-1">{{ $recon->bill->control_number }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Payer Name</span>
                        <p class="my-1">{{ $recon->bill->payer__name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Payer Mobile No.</span>
                        <p class="my-1">{{ $recon->bill->payer_phone_number }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Payer Email</span>
                        <p class="my-1">{{ $recon->bill->payer_email }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Bill Created</span>
                        <p class="my-1">{{ $recon->bill->created_at->toFormattedDateString() }}</p>
                    </div>
                    <div class="col-md-8 mb-3">
                        <span class="font-weight-bold text-uppercase">Bill Description</span>
                        <p class="my-1">{{ $recon->bill->description }}</p>
                    </div>
                </div>
            @else
                <div class="text-center text-muted d-flex justify-content-center align-items-center" style="min-height: 50px;">
                    Bill Details Not Found.
                </div>
            @endif
        </div>
    </div>
@endsection
