@extends('layouts.master')

@section('title','View Properties')

@section('content')
    <div class="row mx-1">
        <div class="col-md-12">
            <livewire:property-tax.property-tax-payment :payment="$paymentExtension->propertyPayment" />
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Property Information</h5>
        </div>
        <div class="card-body">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Property Status</span>
                    <p class="my-1">
                        @include('property-tax.payment-extension.includes.status', ['row' => $paymentExtension])
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Current Due Date</span>
                    <p class="my-1">{{ Carbon\Carbon::parse($paymentExtension->extension_from)->toFormattedDateString() ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Expected Due Date</span>
                    <p class="my-1">{{ $paymentExtension->extension_to ?  \Carbon\Carbon::parse($paymentExtension->extension_to)->toFormattedDateString() ?? 'N/A' : 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Requested By</span>
                    <p class="my-1">{{ $requestedByName ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Requested On</span>
                    <p class="my-1">{{ $paymentExtension->created_at->toFormattedDateString() ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Reason</span>
                    <p class="my-1">{{ $paymentExtension->reasons ?? 'N/A' }}</p>
                </div>
            </div>

        </div>

        <livewire:approval.property-tax-payment-extension-approval-processing modelName="{{ get_class($paymentExtension) }}"  modelId="{{ encrypt($paymentExtension->id) }}"></livewire:approval.property-tax-payment-extension-approval-processing>

@endsection