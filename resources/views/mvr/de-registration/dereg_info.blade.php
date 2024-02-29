<div class="card mt-3">
    <div class="card-header font-weight-bold bg-white d-flex justify-content-between align-items-center">
        <span>{{ __('De-registration Information') }}</span>
    </div>

    <div class="card-body">
        <div class="row my-2">
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Status</span>
                <p class="my-1">
                    @if($reg->status === \App\Enum\MvrDeRegistrationStatus::PENDING)
                        <span class="badge badge-info py-1 px-2"
                              style="font-size: 85%">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('Pending') }}
            </span>
                    @elseif($reg->status === \App\Enum\MvrDeRegistrationStatus::APPROVED)
                        <span class="badge badge-success py-1 px-2"
                              style="font-size: 85%">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('APPROVED') }}
            </span>
                    @elseif($reg->status === \App\Enum\MvrDeRegistrationStatus::CORRECTION)
                        <span class="badge badge-warning py-1 px-2"
                              style="font-size: 85%">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('For Corrections') }}
            </span>
                    @else
                        <span class="badge badge-primary py-1 px-2"
                              style="font-size: 85%">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ strtoupper($reg->status) }}
            </span>
                    @endif
                </p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Reason</span>
                <p class="my-1">{{ $reg->reason->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">De-Registered On</span>
                <p class="my-1">{{ $reg->deregistered_at ?? 'N/A' }}</p>
            </div>
            @if ($reg->clearance_evidence)
                <div class="col-md-3 mb-3">
                    <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                         class="p-2 mb-3 d-flex rounded-sm align-items-center">
                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                        <a target="_blank"
                           href="{{ route('mvr.de-registration.file', encrypt($reg->clearance_evidence)) }}"
                           style="font-weight: 500;" class="ml-1">
                            Clearance Evidence
                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                        </a>
                    </div>
                </div>
            @endif
            @if ($reg->zic_evidence)
                <div class="col-md-3 mb-3">
                    <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                         class="p-2 mb-3 d-flex rounded-sm align-items-center">
                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                        <a target="_blank"
                           href="{{ route('mvr.de-registration.file', encrypt($reg->zic_evidence)) }}"
                           style="font-weight: 500;" class="ml-1">
                            ZIC Evidence
                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                        </a>
                    </div>
                </div>
            @endif
            @if($reg->police_evidence)
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Police Reasons</span>
                    <p class="my-1">{{ $reg->police_evidence }}</p>
                </div>
            @endif
        </div>

    </div>
</div>