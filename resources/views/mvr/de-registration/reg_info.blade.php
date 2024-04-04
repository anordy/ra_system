<div class="card mt-3">
    <div class="card-header font-weight-bold bg-white d-flex justify-content-between align-items-center">
        <span>{{ __('Registration Information') }}</span>
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
                <span class="font-weight-bold text-uppercase">Registration Number</span>
                <p class="my-1">{{ $reg->registration_number ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Plate Number</span>
                <p class="my-1">{{ $reg->plate_number ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Registration Type</span>
                <p class="my-1">{{ $reg->regtype->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Registration Class</span>
                <p class="my-1">{{ $reg->class->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Plate Number Size</span>
                <p class="my-1">{{ $reg->platesize->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Plate Number Color</span>
                <p class="my-1">{{ $reg->platecolor->name ?? 'N/A' }}</p>
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

            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Registered On</span>
                <p class="my-1">{{ $reg->registered_at ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>