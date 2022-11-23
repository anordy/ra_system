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
                    <p class="my-1">{{ $transaction->BillCtrNum }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Transaction Amount</span>
                    <p class="my-1">{{ number_format($transaction->PaidAmt, 2) }} {{ $transaction->CCy }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Transaction Date</span>
                    <p class="my-1">{{ $transaction->TrxDtTm }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Receipt No</span>
                    <p class="my-1">{{ $transaction->PayRefId }}</p>
                </div>
            </div>
        </div>

        <div class="card-body">
            <h6 class="text-uppercase">Transaction Channel Details</h6>
            <hr>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Payment Channel</span>
                    <p class="my-1">{{ $transaction->UsdPayChnl }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Credited Account Number</span>
                    <p class="my-1">{{ $transaction->CtrAccNum }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">PSP Name</span>
                    <p class="my-1">{{ $transaction->PspName ?? '' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">PSP Transaction Id</span>
                    <p class="my-1">{{ $transaction->pspTrxId }}</p>
                </div>
                <div class="col-md-12 mb-3">
                    <span class="font-weight-bold text-uppercase">Remarks</span>
                    <p class="my-1">{{ $transaction->Remarks ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="card-body">
            <h6 class="text-uppercase">Depositors Details</h6>
            <hr>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Payer Name</span>
                    <p class="my-1">{{ $transaction->DptName }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Payer Mobile</span>
                    <p class="my-1">{{ $transaction->DptCellNum ?? 'N/A' }}</p>
                </div>
                @if ($transaction->DptEmailAddr)
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Payer Email</span>
                        <p class="my-1">{{ $transaction->DptEmailAddr }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
