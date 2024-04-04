@if ($row->status === \App\Enum\PublicService\TemporaryClosureStatus::APPROVED)
    <span class="badge badge-success py-1 px-2">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Registered
    </span>
@elseif($row->status === \App\Enum\PublicService\TemporaryClosureStatus::PENDING)
    <span class="badge badge-warning py-1 px-2">
        <i class="bi bi-clock-history mr-1"></i>
        Pending
    </span>
@elseif($row->status === \App\Enum\PublicService\TemporaryClosureStatus::REJECTED)
    <span class="badge badge-danger py-1 px-2">
        <i class="bi bi-clock-history mr-1"></i>
        Rejected
    </span>
@else
    <span class="badge badge-info py-1 px-2">
        <i class="bi bi-clock-history mr-1"></i>
        {{ $row->status  }}
    </span>
@endif
