@extends('layouts.master')

@section('title', $title)

@section('content')
    <div class="card mt-3">
        <div class="card-body">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="to-print-link" data-toggle="tab" href="#all" role="tab"
                        aria-controls="home" aria-selected="true">Application</a>
                </li>
                @if (!empty($application->drivers_license))
                    <li class=x"nav-item" role="presentation">
                        <a class="nav-link" id="to-print-link" data-toggle="tab" href="#license" role="tab"
                            aria-controls="home" aria-selected="true">License</a>
                    </li>
                @endif
            </ul>
            <hr>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane p-2 show active" id="all" role="tabpanel" aria-labelledby="to-print-tab">
                    <div class="row">
                        <div class="col-md-12 mt-1">
                            <h6 class="pt-3 mb-0 font-weight-bold">Application Details</h6>
                            <hr class="mt-2 mb-3" />
                        </div>

                        @if ($application->application_status->name == \App\Models\DlApplicationStatus::STATUS_PENDING_PAYMENT)
                            <div class="col-md-12 mb-3">
                                <div class="alert alert-info">
                                    <div>Pending Payment for <strong>'{{ $application->type }}'</strong> Driver's license
                                        Application </div>
                                    <br>
                                    <div>
                                        <div>
                                            Registration Fee: <strong>
                                                {{ number_format($application->get_latest_bill()->amount ?? 0) }}
                                                TZS</strong><br>
                                        </div>
                                        <div>
                                            Control Number: <strong>{!! $application->get_latest_bill()->control_number ?? ' <span class="text-danger">Not available</span>' !!}</strong>
                                        </div>
                                        @if ($application->get_latest_bill()->control_number ?? null)
                                            <div>
                                                Control Number Expiry: <strong>{!! $application->get_latest_bill()->expiry_date ?? ' <span class="text-danger"></span>' !!}</strong>
                                            </div>
                                        @endif
                                        <br>
                                        @if ($application->get_latest_bill()->zan_trx_sts_code ?? null != \App\Services\ZanMalipo\ZmResponse::SUCCESS)
                                            <a
                                                href="{{ route('control-number.retry', ['id' => encrypt($application->get_latest_bill()->id)]) }}">
                                                <button class="btn btn-secondary btn-sm btn-rounded">
                                                    Request Control Number</button>
                                            </a>
                                        @elseif($application->get_latest_bill()->is_waiting_callback())
                                            <div>Refresh after 30 seconds to get control number</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Application Type</span>
                            <p class="my-1">{{ $application->type }}</p>
                        </div>

                        @if (!empty($application->drivers_license_owner))
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">License Number</span>
                                <p class="my-1">
                                    {{ $application->drivers_license_owner->drivers_licenses()->latest()->first()->license_number }}
                                </p>
                            </div>
                        @endif

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Application Date</span>
                            <p class="my-1">{{ $application->created_at }}</p>
                        </div>

                        @if (!empty($application->loss_report_path))
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Loss Report</span>
                                <p class="my-1"><a class="btn btn-sm btn-success"
                                        href="{{ url('storage/' . $application->loss_report_path) }}">View/Download</a></p>
                            </div>
                        @endif

                        @if (strtolower($application->type) == 'fresh')
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Certificate of competence</span>
                                <p class="my-1"><a
                                        href="{{ route('mvr.files', encrypt($application->certificate_path)) }}">Preview</a>
                                </p>
                            </div>

                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Certificate of competence number</span>
                                <p class="my-1">{{ $application->certificate_number }}</p>
                            </div>

                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Confirmation Number</span>
                                <p class="my-1">{{ $application->confirmation_number }}</p>
                            </div>
                        @endif

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">License Duration</span>
                            <p class="my-1">{{ $application->license_duration->number_of_years }}</p>

                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">License Classes</span>
                            <p class="my-1">
                                @foreach ($application->application_license_classes as $class)
                                    {{ $class->license_class->name }},
                                @endforeach
                            </p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Status</span>
                            <p class="my-1">
                                @if ($application->application_status->name == \App\Models\DlApplicationStatus::STATUS_PENDING_PAYMENT)
                                    <span class="font-weight-bold text-info">
                                        <i class="bi bi-clock-history mr-1"></i>
                                        Pending payment
                                    </span>
                                @elseif($application->application_status->name == \App\Models\DlApplicationStatus::STATUS_COMPLETED)
                                    <span class="font-weight-bold text-success">
                                        <i class="bi bi-check-circle-fill mr-1"></i>
                                        Completed
                                    </span>
                                @elseif($application->application_status->name == \App\Models\DlApplicationStatus::STATUS_PENDING_APPROVAL)
                                    <span class="font-weight-bold text-info">
                                        <i class="bi bi-clock-history mr-1"></i>
                                        Waiting Approval
                                    </span>
                                @elseif($application->application_status->name == \App\Models\DlApplicationStatus::STATUS_INITIATED)
                                    <span class="font-weight-bold text-warning">
                                        <i class="bi bi-pen-fill mr-1"></i>
                                        Not Submitted
                                    </span>
                                @elseif($application->application_status->name == \App\Models\DlApplicationStatus::STATUS_DETAILS_CORRECTION)
                                    <span class="font-weight-bold text-warning">
                                        <i class="bi bi-pen-fill mr-1"></i>
                                        Returned
                                    </span>
                                @else
                                    <span class="font-weight-bold text-info">
                                        <i class="bi bi-clock-history mr-1"></i>
                                        {{ $application->application_status->name }}
                                    </span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-12"></div>

                    </div>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-md-12 mt-1">
                            <h6 class="pt-3 mb-0 font-weight-bold">Applicant Details</h6>
                            <hr class="mt-2 mb-3" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <div style="width: 250px;">
                                @if (strtolower($application->type) == 'fresh' && empty($application->photo_path))
                                    <div
                                        style="border: 1px solid silver; width: 100%; border-radius: 3px; margin-bottom: 3px; padding: 3px">
                                        <img src="{{ url('/images/profile.png') }}" style="width: 100%;">
                                    </div>
                                @else
                                    <div
                                        style="border: 1px solid silver; width: 100%; border-radius: 3px; margin-bottom: 3px; padding: 3px">
                                        <img src="{{ route('drivers-license.license.file', encrypt($application->photo_path ?? $application->drivers_license_owner->photo_path)) }}"
                                            style="width: 100%;">
                                    </div>
                                @endif
                                @if ($application->application_status->name === \App\Models\DlApplicationStatus::STATUS_TAKING_PICTURE)
                                    <button class="btn btn-primary btn-sm btn-block"
                                        onclick="Livewire.emit('showModal', 'drivers-license.capture-passport-modal',{{ $application->id }})">
                                        <i class="fa fa-camera"></i>
                                        Capture Passport
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8 mt-5">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">name</span>
                                    <p class="my-1">{{ $application->taxpayer->fullname() }}</p>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">TIN</span>
                                    <p class="my-1">{{ $application->taxpayer->tin }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Email Address</span>
                                    <p class="my-1">{{ $application->taxpayer->email }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Mobile</span>
                                    <p class="my-1">{{ $application->taxpayer->mobile }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Alternative</span>
                                    <p class="my-1">{{ $application->taxpayer->alt_mobile }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Nationality</span>
                                    <p class="my-1">{{ $application->taxpayer->country->nationality }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span
                                        class="font-weight-bold text-uppercase">{{ $application->taxpayer->identification->name }}
                                        No.</span>
                                    <p class="my-1">{{ $application->taxpayer->id_number }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Date of birth</span>
                                    <p class="my-1">
                                        {{ $application->dob ?? ($application->driver_license_owner->dob ?? '') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end  p-2">
                                @if ($application->application_status->name === \App\Models\DlApplicationStatus::STATUS_INITIATED ||
                                    $application->application_status->name === \App\Models\DlApplicationStatus::STATUS_DETAILS_CORRECTION)
                                    <a href="{{ route('drivers-license.', encrypt($application->id)) }}">
                                        <button class="btn btn-primary btn-sm "><i class="fa fa-check"></i>
                                            Submit
                                        </button>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if (!empty($application->drivers_license))
                    <div class="tab-pane p-2" id="license" role="tabpanel" aria-labelledby="to-print-tab">
                        <div class="row">
                            {{-- <div class="col-3 ">
                                <div style="width: 250px;  max-height: 250px; overflow: hidden;border: 1px solid silver;">
                                    <div style=" width: 100%; border-radius: 3px; margin-bottom: 3px; padding: 3px">
                                        <img src="{{url('storage/'.$application->photo_path)}}" style="width: 100%;">
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <span class="font-weight-bold text-uppercase">License Number</span>
                                        <p class="my-1">{{ $application->drivers_license->license_number }}</p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <span class="font-weight-bold text-uppercase">License Classes</span>
                                        <p class="my-1">
                                            @foreach ($application->application_license_classes as $class)
                                                {{ $class->license_class->name }},
                                            @endforeach
                                        </p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <span class="font-weight-bold text-uppercase">Issued Date</span>
                                        <p class="my-1">
                                            {{ $application->drivers_license->issued_date->format('Y-m-d') }}</p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <span class="font-weight-bold text-uppercase">Expire Date</span>
                                        <p class="my-1">
                                            {{ $application->drivers_license->expiry_date->format('Y-m-d') }}</p>
                                    </div>

                                    @if ($application->application_status->name === \App\Models\DlApplicationStatus::STATUS_LICENSE_PRINTING)
                                        <div class="col-md-6 mb-3">
                                            <span class="font-weight-bold text-uppercase">Print Drivers License</span>
                                            <p class="my-1">
                                                <a
                                                    href="{{ route('drivers-license.license.print', encrypt($application->drivers_license->id)) }}">
                                                    <button class="btn btn-sm btn-success">Print</button>
                                                </a>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if ($application->application_status->name === \App\Models\DlApplicationStatus::STATUS_LICENSE_PRINTING)
                            <div class="row">
                                <div class="col-12">
                                    <div class="modal-footer">
                                        <a
                                            href="{{ route('drivers-license.applications.printed', encrypt($application->id)) }}"><button
                                                class="btn btn-primary">Update as printed</button></a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

            </div>


        </div>
        <br>

    </div>
@endsection
