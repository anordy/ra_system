@extends('layouts.master')

@section('title', 'Payment Details')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div class="text-uppercase font-weight-bold">Bill Details</div>
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
                @if ($bill->cancellation_reason)
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Cancellation Reason</span>
                        <p class="my-1">{{ $bill->cancellation_reason }}</p>
                    </div>
                @endif
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Bill Amount</span>
                    <p class="my-1">{{ number_format($bill->amount, 2) }} {{ $bill->currency }}</p>
                </div>
                @if ($bill->currency != 'TZS')
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
                    <p class="my-1">
                        {{ $bill->createdBy->fullName ?? '' }}
                    </p>
                </div>
            </div>
            <x-bill-structure :bill="$bill" :withCard="false" />
        </div>
    </div>

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            PBZ Payment Details
        </div>
        <div class="card-body p-2">
            <div class="row ">
                <div class="col-md-3 mb-0">
                    <span class="font-weight-bold text-uppercase">PBZ Status</span>
                    <p class="my-1 text-uppercase">{{ $bill->pbz_status ?? 'N/A' }}</p>
                </div>
                @if($tnx = $bill->pbzTransaction)
                    <div class="col-md-3 mb-0">
                        <span class="font-weight-bold text-uppercase">PBZ Amount</span>
                        <p class="my-1 text-uppercase">{{ $bill->pbz_amount ?? 'N/A' }} {{ $bill->pbz_currency }}</p>
                    </div>
                    <div class="col-md-3 mb-0">
                        <span class="font-weight-bold text-uppercase">Bank Ref</span>
                        <p class="my-1 text-uppercase">{{ $tnx->bank_ref ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3 mb-0">
                        <span class="font-weight-bold text-uppercase">Paid At</span>
                        <p class="my-1 text-uppercase">{{ $tnx->transaction_time?->toDayDateTimeString() ?? 'N/A' }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>


    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Payment Details
        </div>
        <div class="card-body p-2">
            <div class="row">
                <div class="col-md-12 table-responsive ">
                    <table class="table table-sm  table-bordered ">
                        <thead>
                            <th>Payer Name</th>
                            <th>Payer Mobile</th>
                            <th>Payer Email</th>
                            <th>Paid Amount</th>
                            <th>Paid At</th>
                            <th>Payment Receipt No</th>
                        </thead>
                        <tbody>
                            @foreach ($bill->bill_payments as $payment)
                                <tr>
                                    <td>{{ $payment->payer_name }}</td>
                                    <td>{{ $payment->payer_mobile ?: 'N/A' }}</td>
                                    <td>{{ $payment->payer_email ?: 'N/A' }}</td>
                                    <td>{{ number_format($payment->paid_amount, 2) }} {{ $payment->currency }}</td>
                                    <td>{{ $payment->trx_time->toDayDateTimeString() }}</td>
                                    <td>{{ $payment->psp_receipt_number }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
