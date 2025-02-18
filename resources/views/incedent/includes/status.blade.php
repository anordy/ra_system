@if($row->status === \App\Enum\RaStatus::PENDING)
    <span class="badge text-uppercase badge-info py-1 px-2 font-percent-85">
                <i class="bi bi-clock-fill mr-1"></i>
                {{ __('Pending') }}
            </span>
@elseif($row->status === \App\Enum\RaStatus::CORRECTION)
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
