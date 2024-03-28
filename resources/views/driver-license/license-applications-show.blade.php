@extends('layouts.master')

@section('title', $title ?? 'N/A')

@section('content')
    <div class="card mt-3">
        <div class="card-body">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="to-print-link" data-toggle="tab" href="#all" role="tab"
                       aria-controls="home" aria-selected="true">Application</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="approval" data-toggle="tab" href="#approval" role="tab"
                       aria-controls="home" aria-selected="true">Approval History</a>
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

                        <div class="col-md-12 mb-3">
                            @if (
                                $application->status === \App\Models\DlApplicationStatus::STATUS_PENDING_PAYMENT ||
                                    $application->payment_status === \App\Enum\PaymentStatus::PENDING)
                                @livewire('drivers-license.payment.fee-payment', ['license' => $application])
                            @endif
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Application Type</span>
                            <p class="my-1">{{ $application->type ?? 'N/A' }}</p>
                        </div>


                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Application Date</span>
                            <p class="my-1">{{ $application->created_at ?? 'N/A' }}</p>
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
                                <p class="my-1">{{ $application->certificate_number ?? 'N/A'}}</p>
                            </div>

                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Confirmation Number</span>
                                <p class="my-1">{{ $application->confirmation_number ?? 'N/A' }}</p>
                            </div>
                        @endif

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">License Duration</span>
                            <p class="my-1">{{ $application->license_duration ?? 'N/A' }} Years</p>

                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">License Classes</span>
                            <p class="my-1">
                                @if($application->application_license_classes)
                                    @foreach ($application->application_license_classes as $class)
                                        {{ $class->license_class->name ?? 'N/A' }},
                                    @endforeach
                                @endif

                            </p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Status</span>
                            <p class="my-1">
                                @if ($application->status == \App\Models\DlApplicationStatus::STATUS_PENDING_PAYMENT)
                                    <span class="font-weight-bold text-info">
                                        <i class="bi bi-clock-history mr-1"></i>
                                        Pending payment
                                    </span>
                                @elseif($application->status == \App\Models\DlApplicationStatus::STATUS_COMPLETED)
                                    <span class="font-weight-bold text-success">
                                        <i class="bi bi-check-circle-fill mr-1"></i>
                                        Completed
                                    </span>
                                @elseif($application->status == \App\Models\DlApplicationStatus::STATUS_PENDING_APPROVAL)
                                    <span class="font-weight-bold text-info">
                                        <i class="bi bi-clock-history mr-1"></i>
                                        Waiting Approval
                                    </span>
                                @elseif($application->status == \App\Models\DlApplicationStatus::STATUS_INITIATED)
                                    <span class="font-weight-bold text-warning">
                                        <i class="bi bi-pen-fill mr-1"></i>
                                        Initiated
                                    </span>
                                @elseif($application->status == \App\Models\DlApplicationStatus::STATUS_DETAILS_CORRECTION)
                                    <span class="font-weight-bold text-warning">
                                        <i class="bi bi-pen-fill mr-1"></i>
                                        Returned
                                    </span>
                                @else
                                    <span class="font-weight-bold text-info">
                                        <i class="bi bi-clock-history mr-1"></i>
                                        {{ $application->status }}
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
                            <div class="width-px-250">
                                @if (strtolower($application->type) == 'fresh' && empty($application->photo_path))
                                    <div
                                            class="dl-photo">
                                        <img src="{{ url('/images/profile.png') }}" class="width-percent-100">
                                    </div>
                                @else
                                    <div
                                            class="dl-photo">
                                        <img src="{{ route('drivers-license.license.file', encrypt($application->photo_path ?? $application->drivers_license_owner->photo_path)) }}"
                                             class="width-percent-100">
                                    </div>
                                @endif
                                @if ($application->status === \App\Models\DlApplicationStatus::STATUS_TAKING_PICTURE)
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
                                    <span class="font-weight-bold text-uppercase">{{ __('name') }}</span>
                                    <p class="my-1">
                                        {{ $applicant->first_name ?? 'N/A' }} {{ $applicant->middle_name ?? ''}} {{ $applicant->last_name ?? 'N/A' }}
                                    </p>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">{{ __('TIN') }}</span>
                                    <p class="my-1">{{ $applicant->tin ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">{{ __('Email Address') }}</span>
                                    <p class="my-1">{{ $applicant->email ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">{{ __('Mobile') }}</span>
                                    <p class="my-1">{{ $applicant->mobile ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">{{ __('Alternative') }}</span>
                                    <p class="my-1">{{ $applicant->alt_mobile ?? 'N/A' }}</p>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">{{ __('Date of birth') }}</span>
                                    <p class="my-1">{{ $applicant->dob ? Carbon\Carbon::parse($applicant->dob)->format('d-m-Y') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
                        <div class="tab-pane fade p-3 show active" id="home" role="tabpanel"
                             aria-labelledby="home-tab">
                            <livewire:approval.mvr.driver-license-approval-processing
                                    modelName='App\Models\DlLicenseApplication' modelId="{{ encrypt($application->id) }}" />
                        </div>
                        <div class="tab-pane fade p-3" id="approval" role="tabpanel" aria-labelledby="approval-tab">
                            <livewire:approval.approval-history-table modelName='App\Models\DlLicenseApplication'
                                                                      modelId="{{ encrypt($application->id) }}" />
                        </div>
                    </div>

                </div>

                @if (!empty($application->drivers_license))
                    <div class="tab-pane p-2" id="license" role="tabpanel" aria-labelledby="to-print-tab">
                        <div class="row mb-2">
                            @if($application->photo_path)
                                <div class="col-4 ">
                                    <div class="dl-photo-2">
                                        <div class="dl-photo">
                                            <img src="{{ url('storage/' . $application->photo_path) }}" class="width-percent-100">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-6">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <span class="font-weight-bold text-uppercase">License Number</span>
                                        <p class="my-1">{{ $application->drivers_license->license_number ?? 'N/A' }}</p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <span class="font-weight-bold text-uppercase">License Classes</span>
                                        <p class="my-1">
                                            @if($application->application_license_classes)
                                                @foreach ($application->application_license_classes as $class)
                                                    {{ $class->license_class->name ?? 'N/A' }},
                                                @endforeach
                                            @endif
                                        </p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <span class="font-weight-bold text-uppercase">Issued Date</span>
                                        <p class="my-1">
                                            {{ $application->drivers_license->issued_date ? $application->drivers_license->issued_date->format('Y-m-d') : 'N/A' }}</p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <span class="font-weight-bold text-uppercase">Expire Date</span>
                                        <p class="my-1">
                                            {{ $application->drivers_license->expiry_date ? $application->drivers_license->expiry_date->format('Y-m-d') : 'N/A' }}</p>
                                    </div>

                                    @if ($application->status === \App\Models\DlApplicationStatus::STATUS_LICENSE_PRINTING)
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
                        @if ($application->status === \App\Models\DlApplicationStatus::STATUS_LICENSE_PRINTING)
                            <div class="row">
                                <div class="col-12">
                                    <div class="modal-footer">
                                        <a
                                                href="{{ route('drivers-license.applications.printed', encrypt($application->id)) }}"><button
                                                    class="btn btn-primary">Update as printed</button></a>
                                    </div>
                                </div>
                            </div>
                        @elseif ($application->status === \App\Models\DlApplicationStatus::STATUS_COMPLETED)
                            <div class="row">
                                <div class="col-12">
                                    <div class="modal-footer">
                                        <a
                                                href="{{ route('drivers-license.applications.printed', encrypt($application->id)) }}"><button
                                                    class="btn btn-primary">Renew License</button></a>
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
