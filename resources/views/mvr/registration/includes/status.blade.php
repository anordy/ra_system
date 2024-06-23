@if($row->status === \App\Enum\MvrRegistrationStatus::PENDING)
    <span class="badge text-uppercase badge-info py-1 px-2 font-percent-85">
                <i class="bi bi-clock-fill mr-1"></i>
                {{ __('Pending') }}
            </span>
@elseif($row->status === \App\Enum\MvrRegistrationStatus::STATUS_REGISTERED)
    <span class="badge text-uppercase badge-success py-1 px-2 font-percent-85">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('Registered') }}
            </span>
@elseif($row->status === \App\Enum\MvrRegistrationStatus::STATUS_DE_REGISTERED)
    <span class="badge text-uppercase badge-danger py-1 px-2 font-percent-85">
                <i class="bi bi-eraser-fill mr-1"></i>
                {{ __('De-registered') }}
            </span>
@elseif($row->status === \App\Enum\MvrRegistrationStatus::STATUS_RETIRED)
    <span class="badge text-uppercase badge-danger py-1 px-2 font-percent-85">
                        <i class="bi bi-backspace-reverse-fill mr-1"></i>
                        {{ __('Retired') }}
                    </span>
@elseif($row->status === \App\Enum\MvrRegistrationStatus::CORRECTION)
    <span class="badge text-uppercase badge-warning py-1 px-2 font-percent-85">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('For Corrections') }}
            </span>
@else
    <span class="badge text-uppercase badge-primary py-1 px-2 font-percent-85">
                <i class="bi bi-circle mr-1"></i>
                {{ $row->status }}
            </span>
@endif
