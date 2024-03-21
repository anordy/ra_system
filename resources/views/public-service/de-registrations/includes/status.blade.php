@if ($row->status === \App\Enum\PublicService\DeRegistrationStatus::APPROVED)
    <span class="badge badge-success py-1 px-2">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __('Registered') }}
    </span>
@elseif($row->status === \App\Enum\PublicService\DeRegistrationStatus::PENDING)
    <span class="badge badge-warning py-1 px-2">
        <i class="bi bi-clock-history mr-1"></i>
        {{ __('Pending') }}
    </span>
@elseif($row->status === \App\Enum\PublicService\DeRegistrationStatus::REJECTED)
    <span class="badge badge-danger py-1 px-2">
        <i class="bi bi-clock-history mr-1"></i>
        {{ __('Rejected') }}
    </span>
@else
    <span class="badge badge-info py-1 px-2">
        <i class="bi bi-clock-history mr-1"></i>
        {{ $row->status  }}
    </span>
@endif
