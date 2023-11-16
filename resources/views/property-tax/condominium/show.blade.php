@extends('layouts.master')

@section('title','Registered Condominium Properties')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">View Condominium Property</h5>
        </div>
        <div class="card-body">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Condominium Status</span>
                    <p class="my-1">
                        @if ($condominium->status === 'complete')
                            <span class="font-weight-bold text-success">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Complete
                            </span>
                        @elseif($condominium->status === 'incomplete')
                            <span class="font-weight-bold text-danger">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Incomplete
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
                    <span class="font-weight-bold text-uppercase">Condominium Name/Number</span>
                    <p class="my-1">{{ $condominium->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Number of Storeys</span>
                    <p class="my-1">{{ $condominium->storeys->count() ?? '0' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Total Number Of Units</span>
                    <p class="my-1">{{ $condominium->units->count() ?? '0' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Region</span>
                    <p class="my-1">{{ $condominium->region_id ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">District</span>
                    <p class="my-1">{{ $condominium->district_id ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Ward</span>
                    <p class="my-1">{{ $condominium->ward_id ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Street</span>
                    <p class="my-1">{{ $condominium->street_id ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Date of Registration</span>
                    <p class="my-1">{{ $condominium->created_at->toFormattedDateString() ?? 'N/A' }}</p>
                </div>

        </div>

            @foreach($condominium->storeys as $storey)
                <hr>
                <div class="row m-2 pt-3">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Storey Information</span>
                        <p class="my-1">Storey Number {{ $storey->number ?? 'N/A' }}</p>
                        <p class="my-1">Number of Units: {{ $storey->units->count() ?? 'N/A' }}</p>

                    </div>
                    <div class="col-md-8 mb-3">
                        <div class="row pt-3">
                            @foreach($storey->units as $unit)
                                <div class="col-md-6 mb-3">
                                    <span class="font-weight-bold text-uppercase">Unit Name/Number</span>
                                    <p class="my-1">{{ $unit->name ?? 'N/A' }}</p>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            @endforeach

    </div>
@endsection