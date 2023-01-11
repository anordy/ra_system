@extends('layouts.master')

@section('title', 'Taxpayer Registration Details')

@section('content')
    <div class="card mt-3">
        <div class="card-header bg-white font-weight-bold">
            Taxpayer Registration Details
        </div>
        <div class="card-body">
            <div class="row my-2">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Application Reference No.</span>
                    <p class="my-1">{{ $taxPayer->reference_no }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Full Name</span>
                    <p class="my-1">{{ "{$taxPayer->first_name} {$taxPayer->middle_name} {$taxPayer->last_name}" }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Email Address</span>
                    <p class="my-1">{{ $taxPayer->email }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Mobile</span>
                    <p class="my-1">{{ $taxPayer->mobile }}</p>
                </div>
                @if ($taxPayer->alt_mobile)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Alternative Mobile</span>
                        <p class="my-1">{{ $taxPayer->alt_mobile }}</p>
                    </div>
                @endif
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Nationality</span>
                    <p class="my-1">{{ $taxPayer->country->nationality }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Region</span>
                    <p class="my-1">{{ $taxPayer->region->name ?? 'N/A'}}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">District</span>
                    <p class="my-1">{{ $taxPayer->district->name ?? 'N/A'}}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Ward</span>
                    <p class="my-1">{{ $taxPayer->ward->name ?? 'N/A'}}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Street</span>
                    <p class="my-1">{{ $taxPayer->street->name ?? 'N/A' }}</p>
                </div>
            </div>
            <hr />
            <div class="row">
                @if ($taxPayer->nida_no)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">NIDA No.</span>
                        <p class="my-1">{{ $taxPayer->nida_no }}</p>
                    </div>
                @endif
                @if ($taxPayer->zanid_no)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">ZANID No.</span>
                        <p class="my-1">{{ $taxPayer->zanid_no }}</p>
                    </div>
                @endif
                @if ($taxPayer->passport_no)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Passport No.</span>
                        <p class="my-1">{{ $taxPayer->passport_no }}</p>
                    </div>
                @endif
                @if ($taxPayer->work_permit)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Residence Permit</span>
                        <p class="my-1">{{ $taxPayer->work_permit }}</p>
                    </div>
                @endif
                @if ($taxPayer->residence_permit)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Work Permit</span>
                        <p class="my-1">{{ $taxPayer->residence_permit }}</p>
                    </div>
                @endif
            </div>
            <hr />
            <div class="row">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Location</span>
                    <p class="my-1">{{ $taxPayer->location }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Street</span>
                    <p class="my-1">{{ $taxPayer->street->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Physical Address</span>
                    <p class="my-1">{{ $taxPayer->physical_address }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
