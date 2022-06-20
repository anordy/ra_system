@extends('layouts.master')

@section('title', 'Taxpayer Registration Details')

@section('content')
    <div class="card mt-3">
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
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Alternative Mobile</span>
                    <p class="my-1">{{ $taxPayer->alt_mobile }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Nationality</span>
                    <p class="my-1">{{ $taxPayer->country->nationality }}</p>
                </div>

            </div>
            <hr />
            <div class="row">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ $taxPayer->identification->name }} No.</span>
                    <p class="my-1">{{ $taxPayer->id_number }}</p>
                </div>
                @if($taxPayer->work_permit)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Residence Permit</span>
                        <p class="my-1">{{ $taxPayer->work_permit }}</p>
                    </div>
                @endif
                @if($taxPayer->residence_permit)
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
                    <p class="my-1">{{ $taxPayer->street }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Physical Address</span>
                    <p class="my-1">{{ $taxPayer->physical_address }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection