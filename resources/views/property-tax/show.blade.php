@extends('layouts.master')

@section('title','View Properties')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Property Information</h5>
        </div>
        <div class="card-body">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Property Status</span>
                    <p class="my-1">
                        @if ($property->status === 'approved')
                            <span class="font-weight-bold text-success">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Approved
                            </span>
                        @elseif($property->status === 'pending')
                            <span class="font-weight-bold text-warning">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Pending
                            </span>
                        @else
                            <span class="font-weight-bold text-info">
                                <i class="bi bi-clock-history mr-1"></i>
                                Unknown Status
                            </span>
                        @endif
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Property Name/Number</span>
                    <p class="my-1">{{ $property->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Region</span>
                    <p class="my-1">{{ $property->region->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">District</span>
                    <p class="my-1">{{ $property->district->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Ward</span>
                    <p class="my-1">{{ $property->ward->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Street</span>
                    <p class="my-1">{{ $property->street->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Property Type</span>
                    <p class="my-1">{{ $property->type ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Usage Type</span>
                    <p class="my-1">{{ $property->usage_type ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Registered By</span>
                    <p class="my-1">{{ $property->taxpayer->first_name ?? 'N/A' }} {{ $property->taxpayer->last_name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Date of Registration</span>
                    <p class="my-1">{{ $property->created_at->toFormattedDateString() ?? 'N/A' }}</p>
                </div>
            </div>

        </div>

        <div class="card-body">
            <div class="card-header">
                <h5 class="text-uppercase">Responsible Person</h5>
            </div>
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Name</span>
                    <p class="my-1">{{ $property->responsible->first_name ?? 'N/A' }} {{ $property->responsible->middle_name }} {{ $property->responsible->last_name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Gender</span>
                    <p class="my-1">{{ $property->responsible->gender ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Date of Birth</span>
                    <p class="my-1">{{ $property->responsible->date_of_birth->toFormattedDateString() ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Email</span>
                    <p class="my-1">{{ $property->responsible->email ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Mobile</span>
                    <p class="my-1">{{ $property->responsible->mobile ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Mobile</span>
                    <p class="my-1">{{ $property->responsible->mobile ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Address</span>
                    <p class="my-1">{{ $property->responsible->address ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">ID Type</span>
                    <p class="my-1">{{ $property->responsible->idType->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">ID Number</span>
                    <p class="my-1">{{ $property->responsible->id_number ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <livewire:property-tax.bill-preview propertyId="{{ encrypt($property->id) }}"></livewire:property-tax.bill-preview>

        <livewire:approval.property-tax-approval-processing modelName="{{ get_class($property) }}"
                                                            modelId="{{ encrypt($property->id) }}"></livewire:approval.property-tax-approval-processing>

@endsection