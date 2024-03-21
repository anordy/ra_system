@if ($value === \App\Models\BusinessStatus::DRAFT)
    <span class="badge badge-success py-1 px-2 danger-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Rejected
    </span>
@elseif($value === \App\Models\BusinessStatus::PENDING)
    <span class="badge badge-success py-1 px-2 pending-status">
        <i class="bi bi-clock mr-1"></i>
        Pending
    </span>
@elseif ($value === \App\Models\BusinessStatus::APPROVED)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Approved
    </span>
@elseif($value === \App\Models\BusinessStatus::CORRECTION)
    <span class="badge badge-success py-1 px-2 danger-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Correction
    </span>
@elseif($value === \App\Models\BusinessStatus::TEMP_CLOSED)
    <span class="badge badge-success py-1 px-2 danger-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Temp Closed
    </span>
@elseif($value === \App\Models\BusinessStatus::DEREGISTERED)
    <span class="badge badge-success py-1 px-2 danger-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Deregistered
    </span>
@elseif($value === \App\Models\BusinessStatus::REJECTED)
    <span class="badge badge-success py-1 px-2 danger-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Regected
    </span>
@endif

