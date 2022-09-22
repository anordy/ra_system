@extends('layouts.master')

@section('title', 'Payment Details')

@section('content')
    @php($bill = $payment->bill)
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Bill Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Control Number</span>
                    <p class="my-1">{{ $bill->control_number }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Status</span>
                    <p class="my-1 text-uppercase">{{ $bill->status }}</p>
                </div>
                @if($bill->cancellation_reason)
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Cancellation Reason</span>
                        <p class="my-1">{{ $payment->cancellation_reason }}</p>
                    </div>
                @endif
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Bill Amount</span>
                    <p class="my-1">{{ number_format($bill->amount, 2) }} {{ $bill->currency }}</p>
                </div>
                @if($bill->currency != 'TZS')
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Equivalent Amount</span>
                        <p class="my-1">{{ number_format($bill->equivalent_amount, 2) }} TZS</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Exchange Rate</span>
                        <p class="my-1">{{ $bill->exchange_rate }}</p>
                    </div>
                @endif
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Control No. Expiration Date</span>
                    <p class="my-1">{{ $bill->expire_date->toDayDateTimeString() }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Bill Created At</span>
                    <p class="my-1">{{ $bill->created_at->toDayDateTimeString() }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Bill Created By</span>
                    <p class="my-1">{{ $bill->createdBy->fullName }}</p>
                </div>
            </div>
            <x-bill-structure :bill="$bill" :withCard="false" />
        </div>
    </div>
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Payment Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Payer Name</span>
                    <p class="my-1">{{ $payment->payer_name }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Payer Email</span>
                    <p class="my-1">{{ $payment->payer_email }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Payer Mobile</span>
                    <p class="my-1">{{ $payment->payer_mobile ?: 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Paid Amount</span>
                    <p class="my-1">{{ number_format($payment->paid_amount, 2) }} {{ $payment->currency }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Paid At</span>
                    <p class="my-1">{{ $payment->trx_time->toDayDateTimeString() }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Payment Receipt No.</span>
                    <p class="my-1">{{ $payment->psp_receipt_number }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection