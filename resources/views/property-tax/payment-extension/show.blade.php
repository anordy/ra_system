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
                    <span class="font-weight-bold text-uppercase">Property Name/Number</span>
                    <p class="my-1">{{ $paymentExtension->propertyPayment->property->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Unit Registration Number</span>
                    <p class="my-1">{{ $paymentExtension->propertyPayment->property->urn ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Region</span>
                    <p class="my-1">{{ $paymentExtension->propertyPayment->property->region->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">District</span>
                    <p class="my-1">{{ $paymentExtension->propertyPayment->property->district->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Ward</span>
                    <p class="my-1">{{ $paymentExtension->propertyPayment->property->ward->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Street</span>
                    <p class="my-1">{{ $paymentExtension->propertyPayment->property->street->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Property Type</span>
                    <p class="my-1">{{ formatEnum($paymentExtension->propertyPayment->property->type) ?? 'N/A' }}</p>
                </div>
                @if ($paymentExtension->propertyPayment->property->type === \App\Enum\PropertyTypeStatus::HOTEL)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Hotel Stars</span>
                        <p class="my-1">{{ $paymentExtension->propertyPayment->property->star->name ?? 'N/A' }}</p>
                    </div>
                @endif
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Usage Type</span>
                    <p class="my-1">{{ formatEnum($paymentExtension->propertyPayment->property->usage_type) ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Registered By</span>
                    <p class="my-1">{{ $paymentExtension->propertyPayment->property->taxpayer->first_name ?? 'N/A' }}
                        {{ $paymentExtension->propertyPayment->property->taxpayer->last_name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Date of Registration</span>
                    <p class="my-1">{{ $paymentExtension->propertyPayment->property->created_at->toFormattedDateString() ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="card-header">
            <h5 class="text-uppercase">Payment Extension Information</h5>
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