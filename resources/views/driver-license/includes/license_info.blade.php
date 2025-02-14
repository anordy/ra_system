<div class="card">
    <div class="card-header">
        Application Details
    </div>
    <div class="card-body row">
        <div class="col-md-3 mb-3">
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

        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Application Type</span>
            <p class="my-1">{{ $application->type ?? 'N/A' }} @if($application->duplicate_type) {{ $application->duplicate_type }}  @endif</p>
        </div>


        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Application Date</span>
            <p class="my-1">{{ $application->created_at ? \Carbon\Carbon::create($application->created_at)->format('d-m-Y') : 'N/A' }}</p>
        </div>

        @if (!empty($application->lost_report_path))
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Loss Report</span>
                <p class="my-1">
                    <a class="btn btn-sm btn-success" target="_blank"
                       href="{{ route('mvr.files', encrypt($application->lost_report_path)) }}">
                        <i class="bi bi-eye mr-1"></i> Preview Report
                    </a>
                </p>
            </div>
        @endif

        @if ($application->type == \App\Enum\DlFeeType::FRESH)
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Confirmation Number</span>
                <p class="my-1">{{ $application->confirmation_number ?? 'N/A' }}</p>
            </div>
        @endif

        @if($application->license_duration_id)
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">License Duration</span>
                <p class="my-1">{{ $application->license_duration->description ?? 'N/A' }}</p>
            </div>
        @endif


        @if($application->licenseRestrictions->count())
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">{{ __('License Restrictions') }}</span>
                <ul>
                    @foreach ($application->licenseRestrictions as $licenseRestriction)
                        <li>
                            {{ $licenseRestriction->restriction->description }}
                            ({{ $licenseRestriction->restriction->symbol }})
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="col-md-12 row">
            @foreach($application->certificates ?? [] as $certificate)
                <div class="col-md-3">
                    <div class="p-2 mb-3 d-flex rounded-sm align-items-center file-item">
                        <i class="bi bi-file-earmark-pdf-fill px-2 file-icon"></i>
                        <a target="_blank"
                           href="{{ route('mvr.files', encrypt($certificate->location)) }}"
                           class="ml-1">
                            Completion Certificate #{{ $loop->iteration }}
                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        License Information
        <div class="card-tools">
            @if ($application->status === \App\Models\DlApplicationStatus::STATUS_LICENSE_PRINTING)
                <a target="_blank" class="btn btn-primary text-white"
                   href="{{ route('drivers-license.license.print', encrypt($application->drivers_license->id)) }}">
                    <i class="bi bi-printer-fill mr-1"></i> PRINT LICENSE
                </a>
            @endif
            @if ($application->status === \App\Models\DlApplicationStatus::STATUS_LICENSE_PRINTING)
                <a href="{{ route('drivers-license.applications.printed', encrypt($application->id)) }}">
                    <button class="btn btn-success">
                        <i class="bi bi-check2-circle mr-1"></i> UPDATE AS PRINTED
                    </button>
                </a>
            @endif
        </div>
    </div>
    <div class="card-body row">
        <div class="col">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">License Number</span>
                    <p class="my-1">{{ $application->drivers_license->license_number ?? 'N/A' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">License Restrictions</span>
                    @if(count($application->licenseRestrictions ?? []))
                        <ol class="mx-0">
                            @foreach($application->licenseRestrictions as $licenseRestrictions)
                                <li>{{ $licenseRestrictions->restriction->description ?? 'N/A' }}</li>
                            @endforeach
                        </ol>
                    @else
                        <p class="my-1">None</p>
                    @endif

                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Issued Date</span>
                    <p class="my-1">
                        {{ $application->drivers_license->issued_date ? $application->drivers_license->issued_date->format('d-m-Y') : 'N/A' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Expire Date</span>
                    <p class="my-1">
                        {{ $application->drivers_license->expiry_date ? $application->drivers_license->expiry_date->format('d-m-Y') : 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        License Classes
    </div>
    <div class="card-body">
        @foreach($application->application_license_classes ?? [] as $class)
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Class Name @if(!$class->is_initiation_accepted) (Newly Added) @endif</span>
                    <p class="my-1">{{ $class->license_class->name ?? 'N/A' }}
                        - {{ $class->license_class->description ?? 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Certificate Number</span>
                    <p class="my-1">{{ $class->certificate_number ?? 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Certificate Date</span>
                    <p class="my-1">{{ $class->certificate_date ?? 'N/A' }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="card">
    <div class="card-header">
        Applicant Details
    </div>
    <div class="card-body row">
        <div class="col-auto px-4">
            @if ($application->type == \App\Enum\DlFeeType::FRESH && empty($application->photo_path))
                <img class="dl-passport shadow" src="{{ url('/images/profile.png') }}" alt="Passport">
            @else
                <img class="dl-passport shadow"
                     src="{{ route('drivers-license.license.file', encrypt($application->photo_path)) }}"
                     alt="Passport">
            @endif
            @if ($application->status === \App\Models\DlApplicationStatus::STATUS_TAKING_PICTURE)
                <button class="btn btn-primary btn-sm btn-block mt-3"
                        onclick="Livewire.emit('showModal', 'drivers-license.capture-passport-modal',{{ $application->id }})">
                    <i class="bi bi-camera-fill mr-1"></i>
                    Capture Passport
                </button>
            @endif
{{--            @if ($application->status === \App\Models\DlApplicationStatus::STATUS_COMPLETED)--}}
{{--                <button class="btn btn-primary btn-sm btn-block mt-3"--}}
{{--                        onclick="Livewire.emit('showModal', 'drivers-license.capture-passport-modal',{{ $application->id }})">--}}
{{--                    <i class="bi bi-camera-fill mr-1"></i>--}}
{{--                    Re-capture Passport--}}
{{--                </button>--}}
{{--            @endif--}}
        </div>
        <div class="col">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Name') }}</span>
                    <p class="my-1">
                        {{ $application->first_name ?? 'N/A' }} {{ $application->middle_name ?? ''}} {{ $application->last_name ?? 'N/A' }}
                    </p>
                </div>
                @if(isset($application->taxpayer->tin))
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">{{ __('TIN') }}</span>
                        <p class="my-1">{{ $application->taxpayer->tin ?? 'N/A' }}</p>
                    </div>
                @endif
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Email Address') }}</span>
                    <p class="my-1">{{ $application->taxpayer->email ?? 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Mobile') }}</span>
                    <p class="my-1">{{ $application->taxpayer->mobile ?? 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Alternative') }}</span>
                    <p class="my-1">{{ $application->taxpayer->alt_mobile ?? 'N/A' }}</p>
                </div>
                @if(isset($application->taxpayer->date_of_birth))
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">{{ __('Date of birth') }}</span>
                        <p class="my-1">{{ $application->taxpayer->date_of_birth ? Carbon\Carbon::parse($application->taxpayer->date_of_birth)->format('d-m-Y') : 'N/A' }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>