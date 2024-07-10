@extends('layouts.master')

@section('title', 'View Payment')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            {{ __('View Taxpayer Payment') }}
        </div>

        @if($payment->status === \App\Enum\ReturnStatus::APPROVED)
            <div class="row m-2 pt-3">
                <div class="col-md-12">
                    <livewire:taxpayer-ledger.bill-payment :payment="$payment"/>
                </div>
            </div>
        @endif

        <div class="card-body">
            <div class="row m-2 pt-3">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Control Number') }}</span>
                    <p class="my-1">{{ $payment->latestBill->control_number ?? 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Total Amount') }}</span>
                    <p class="my-1">{{ $payment->currency }} {{ number_format($payment->total_amount ?? 0, 2) }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Is Partial') }}</span>
                    <p class="my-1">{{ $payment->is_partial ? 'Yes' : 'No' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Status') }}</span>
                    <p class="my-1">{{ $payment->status ?? 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Status') }}</span>
                    <p class="my-1">{{ $payment->ledger_ids ?? 'N/A' }}</p>
                </div>
            </div>

            <span class="font-weight-bold mx-4 mt-4 text-uppercase">{{ __('Payment Items') }}</span>
            <hr>

            @if(isset($payment->items))
                @foreach($payment->items as $item)
                    <div class="row m-2 pt-3">
                        <div class="col-md-3 mb-3">
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Debit Number') }}</span>
                            <p class="my-1">{{ $item->ledger->debit_no ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Tax Type') }}</span>
                            <p class="my-1">{{ $item->taxtype->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Amount') }}</span>
                            <p class="my-1">{{ $item->currency }} {{ number_format($item->amount ?? 0, 2) }}</p>
                        </div>
                    </div>
                    <hr>
                @endforeach
            @endif
        </div>
    </div>
@endsection
