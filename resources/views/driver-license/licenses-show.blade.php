@extends('layouts.master')

@section('title', 'Driver\'s License')

@section('content')
    <div class="card mt-3">
        <div class="card-header ">
            Driver's License Details
            <div class="card-tools">
                <a target="_blank"
                   href="{{route('drivers-license.license.print',encrypt($license->id))}}">
                    <button class="btn btn-sm btn-success">
                        <i class="bi bi-file-earmark-pdf-fill mr-1"></i> Print Driver's Licence
                    </button>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">License Number</span>
                        <p class="my-1">{{ $license->license_number ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">License Classes</span>
                        <p class="my-1">
                            @foreach($license->drivers_license_classes as $class)
                                {{ $class->license_class->name ?? 'N/A' }},
                            @endforeach
                        </p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Issued Date</span>
                        <p class="my-1">{{ $license->issued_date ? $license->issued_date->format('Y-m-d') : 'N/A' }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Expire Date</span>
                        <p class="my-1">{{ $license->expiry_date ? $license->expiry_date->format('Y-m-d') : 'N/A' }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Certificate Number</span>
                        <p class="my-1">{{ $license->drivers_license_owner->certificate_number ?? 'N/A'}}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Confirmation Number</span>
                        <p class="my-1">{{ $license->drivers_license_owner->confirmation_number ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Competence Number</span>
                        <p class="my-1">{{ $license->drivers_license_owner->competence_number ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <p class="my-1">

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Driver's Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="width-px-250">
                        <div class="dl-photo">
                            <img class="width-percent-100"
                                 src="{{ route('drivers-license.license.file', encrypt($license->drivers_license_owner->photo_path)) }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">name</span>
                            <p class="my-1">{{ $license->drivers_license_owner->fullname() }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">TIN</span>
                            <p class="my-1">{{ $license->drivers_license_owner->tin }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Email Address</span>
                            <p class="my-1">{{ $license->drivers_license_owner->email }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Mobile</span>
                            <p class="my-1">{{ $license->drivers_license_owner->mobile }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Alternative</span>
                            <p class="my-1">{{ $license->drivers_license_owner->alt_mobile }}</p>
                        </div>

                        @if ($license->drivers_license_owner->zanid_no)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">ZANID No.</span>
                                <p class="my-1">{{ $license->drivers_license_owner->zanid_no }}</p>
                            </div>
                        @endif
                        @if ($license->drivers_license_owner->nida_no)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">NIDA No.</span>
                                <p class="my-1">{{ $license->drivers_license_owner->nida_no }}</p>
                            </div>
                        @endif
                        @if ($license->drivers_license_owner->passport_no)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Passport No.</span>
                                <p class="my-1">{{ $license->drivers_license_owner->passport_no }}</p>
                            </div>
                        @endif
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Date of birth</span>
                            <p class="my-1">{{ $license->drivers_license_owner->dob  }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

