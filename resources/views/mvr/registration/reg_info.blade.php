<div class="card mt-3">
    <div class="card-header font-weight-bold bg-white">
        Registration Information - {{ $reg->chassis->chassis_number ?? 'N/A'  }}
    </div>
    <div class="card-body">
        <div class="row my-2">
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Status</span>
                <p class="my-1">
                    @if($reg->status === \App\Enum\MvrRegistrationStatus::PENDING)
                        <span class="badge badge-info py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('Pending') }}
            </span>
                    @elseif($reg->status === \App\Enum\MvrRegistrationStatus::STATUS_REGISTERED)
                        <span class="badge badge-success py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('Registered') }}
                        </span>
                @elseif($reg->status === \App\Enum\MvrRegistrationStatus::STATUS_RETIRED)
                        <span class="badge badge-danger py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('Retired') }}
            </span>
                    @elseif($reg->status === \App\Enum\MvrRegistrationStatus::CORRECTION)
                        <span class="badge badge-warning py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('For Corrections') }}
            </span>
                    @else
                        <span class="badge badge-primary py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ $reg->status }}
            </span>
                    @endif
                </p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Registerer Name</span>
                <p class="my-1">{{ $reg->taxpayer->fullname ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Registration Number</span>
                <p class="my-1">{{ $reg->registration_number ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Registered On</span>
                <p class="my-1">{{ $reg->registered_at ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Registration Class</span>
                <p class="my-1">{{ $reg->class->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Registration Type</span>
                <p class="my-1">{{ $reg->regtype->name ?? 'N/A' }}</p>
            </div>
            @if($reg->plate_type)
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Registration No. Type</span>
                    <p class="my-1">{{ $reg->plate_type->name ?? 'N/A' }}</p>
                </div>
            @endif
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Plate Number</span>
                <p class="my-1">{{ $reg->plate_number ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Plate Number Size</span>
                <p class="my-1">{{ $reg->platesize->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Plate Number Color</span>
                <p class="my-1">{{ $reg->regtype->color ? ($reg->regtype->color->color ?? 'N/A') : 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Register Type</span>
                <p class="my-1">{{ $reg->register_type ?? 'N/A' }}</p>
            </div>

            @if($reg->agent)
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Is Registration For Agent?</span>
                    <p class="my-1">{{ $reg->is_agent_registration ? 'Yes' : 'No' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Use Agent Company Name?</span>
                    <p class="my-1">{{ $reg->use_company_name ? 'Yes' : 'No' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Registrant TIN</span>
                    <p class="my-1">{{ $reg->registrant_tin ?? 'N/A' }}</p>
                </div>
            @endif

            @if($reg->inspection)
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Inspection Mileage</span>
                    <p class="my-1">{{ number_format($reg->inspection->inspection_mileage ?? 0) }} KM</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Inspection Date</span>
                    <p class="my-1">{{ Carbon\Carbon::parse($reg->inspection->inspection_date)->format('d M Y') }}</p>
                </div>
                <div class="col-md-4">
                    <div class="p-2 mb-3 d-flex rounded-sm align-items-center file-item">
                        <i class="bi bi-file-earmark-pdf-fill px-2 file-icon"></i>
                        <a target="_blank"
                           href="{{ route('mvr.files', encrypt($reg->inspection->report_path)) }}"
                           class="ml-1">
                            Inspection Report
                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

@if ($reg->registrant_tin)
    @livewire('tra.tin-verification', ['tinNumber' => $reg->registrant_tin])
@endif
