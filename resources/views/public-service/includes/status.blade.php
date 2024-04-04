@if ($row->status === \App\Enum\PublicServiceMotorStatus::REGISTERED)
    <span class="badge badge-success py-1 px-2">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Registered
    </span>
@elseif($row->status === \App\Enum\PublicServiceMotorStatus::PENDING)
    <span class="badge badge-warning py-1 px-2">
        <i class="bi bi-clock-history mr-1"></i>
        Pending
    </span>
@elseif($row->status === \App\Enum\PublicServiceMotorStatus::DEREGISTERED)
    <span class="badge badge-danger py-1 px-2">
        <i class="bi bi-x-circle-fill mr-1"></i>
        De-registered
    </span>
@elseif($row->status === \App\Enum\PublicServiceMotorStatus::TEMP_CLOSED)
    <span class="badge badge-secondary py-1 px-2">
        <i class="bi bi-stop-circle mr-1"></i>
        Temporary Closed
    </span>
@else
    <span class="badge badge-info py-1 px-2">
        <i class="bi bi-info-square-fill mr-1"></i>
        {{ $row->status }}
    </span>
@endif
