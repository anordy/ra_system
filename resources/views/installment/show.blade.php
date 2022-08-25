@extends('layouts.master')

@section('title', 'Installment Details')

@section('content')
    <div class="container-fluid">
        @if($installment->getNextPaymentDate())
            <livewire:installment.installment-payment :installment="$installment" />
        @endif
    </div>
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Installment Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Installment Total Amount</span>
                    <p class="my-1">{{ $installment->currency }}. {{ number_format($installment->amount, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">No. of Installments </span>
                    <p class="my-1">{{ $installment->installment_count }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Amount per Installment</span>
                    <p class="my-1">{{ $installment->currency }}. {{ number_format($installment->amount/$installment->installment_count, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Start Date</span>
                    <p class="my-1">{{ $installment->installment_from->toDayDateTimeString() }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Due Date</span>
                    <p class="my-1">{{ $installment->installment_to->toDayDateTimeString() }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Type</span>
                    <p class="my-1">{{ $installment->taxType->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Status</span>
                    <p class="my-1 text-capitalize">{{ $installment->status }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Installment Items
        </div>
        <div class="card-body">
            <livewire:installment.installment-items-table :installment="$installment" />
        </div>
    </div>

@endsection