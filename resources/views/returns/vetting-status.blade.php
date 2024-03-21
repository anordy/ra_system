@if ($value == \App\Enum\VettingStatus::VETTED)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __('Vetted') }}
    </span>
@elseif ($value == \App\Enum\VettingStatus::CORRECTION)
    <span class="badge badge-danger py-1 px-2 draft-status">
        <i class="bi bi-clock-history mr-1"></i>
        {{ __('On Correction') }}
    </span>
@elseif ($value == \App\Enum\VettingStatus::CORRECTED)
    <span class="badge badge-danger py-1 px-2 pending-status">
        <i class="bi bi-record-circle mr-1"></i>
        {{ __('Corrected') }}
    </span>
@else
    <span class="badge badge-success py-1 px-2 pending-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ $value }}
    </span>
@endif

