@extends('layouts.master')

@section('title', 'Reconciliation Transaction Details')

@section('content')
    <div class="card rounded-0">

        <div class="card-body">
            <h6 class="text-uppercase">Bill Details</h6>
            <hr>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Control Number</span>
                    <p class="my-1">{{ $transaction->billctrnum }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Transaction Amount</span>
                    <p class="my-1">{{ number_format($transaction->paidamt, 2) }} {{ $transaction->ccy }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Transaction Date</span>
                    <p class="my-1">{{ $transaction->trxdttm }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Receipt No</span>
                    <p class="my-1">{{ $transaction->payrefid }}</p>
                </div>
            </div>
        </div>

        <div class="card-body">
            <h6 class="text-uppercase">Transaction Channel Details</h6>
            <hr>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Payment Channel</span>
                    <p class="my-1">{{ $transaction->usdpaychnl }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Credited Account Number</span>
                    <p class="my-1">{{ $transaction->ctraccnum }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">PSP Name</span>
                    <p class="my-1">{{ $transaction->pspname ?? '' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">PSP Transaction Id</span>
                    <p class="my-1">{{ $transaction->psptrxid }}</p>
                </div>
                <div class="col-md-12 mb-3">
                    <span class="font-weight-bold text-uppercase">Remarks</span>
                    <p class="my-1">{{ $transaction->remarks ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="card-body">
            <h6 class="text-uppercase">Depositors Details</h6>
            <hr>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Payer Name</span>
                    <p class="my-1">{{ $transaction->dptname }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Payer Mobile</span>
                    <p class="my-1">{{ $transaction->dptcellnum ?? 'N/A' }}</p>
                </div>
                @if ($transaction->dptemailaddr)
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Payer Email</span>
                        <p class="my-1">{{ $transaction->dptemailaddr }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
