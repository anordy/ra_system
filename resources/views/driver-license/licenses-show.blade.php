@extends('layouts.master')

@section('title', 'Driver\'s License')

@section('content')
    <div class="card mt-3">
        <div class="card-body">

            <div class="row">
                <div class="col-md-12 mt-1">
                    <h6 class="pt-3 mb-0 font-weight-bold">License Details</h6>
                    <hr class="mt-2 mb-3"/>
                </div>

                <div class="col-12">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">License Number</span>
                            <p class="my-1">{{ $license->license_number }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">License Classes</span>
                            <p class="my-1">
                                @foreach($license->drivers_license_classes as $class)
                                    {{ $class->license_class->name }},
                                @endforeach
                            </p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Issued Date</span>
                            <p class="my-1">{{ $license->issued_date->format('Y-m-d') }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Expire Date</span>
                            <p class="my-1">{{ $license->expiry_date->format('Y-m-d') }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Certificate Number</span>
                            <p class="my-1">{{ $license->drivers_license_owner->certificate_number }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Confirmation Number</span>
                            <p class="my-1">{{ $license->drivers_license_owner->confirmation_number }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Competence Number</span>
                            <p class="my-1">{{ $license->drivers_license_owner->competence_number }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Print Drivers License</span>
                            <p class="my-1">
                                <a href="{{route('drivers-license.license.print',encrypt($license->id))}}">
                                    <button class="btn btn-sm btn-success">show</button>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            <br>
            <div class="row">
                <div class="col-md-12 mt-1">
                    <h6 class="pt-3 mb-0 font-weight-bold">Driver's Details</h6>
                    <hr class="mt-2 mb-3"/>
                </div>

                <div class="col-md-4 mb-3">
                    <div style="width: 250px;">
                        <div style="border: 1px solid silver; width: 100%; border-radius: 3px; margin-bottom: 3px; padding: 3px">
                            <img src="{{ route('drivers-license.license.file', encrypt($license->drivers_license_owner->photo_path)) }}" style="width: 100%;">
                        </div>
                    </div>
                </div>
                <div class="col-md-8 mt-5">
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
        <br>

    </div>
@endsection

