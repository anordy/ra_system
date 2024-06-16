@if ($value === \App\Models\BranchStatus::APPROVED)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Approved
    </span>
@elseif($value === \App\Models\BranchStatus::REJECTED)
    <span class="badge badge-success py-1 px-2 danger-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Rejected
    </span>
@elseif($value === \App\Models\BranchStatus::TEMP_CLOSED)
    <span class="badge badge-success py-1 px-2 danger-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Temporary Closed
    </span>
@elseif($value === \App\Models\BranchStatus::DE_REGISTERED)
    <span class="badge badge-success py-1 px-2 danger-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        De-registered
    </span>
@elseif($value === \App\Models\BranchStatus::CORRECTION)
    <span class="badge badge-success py-1 px-2 danger-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Correction
    </span>
@else
    <span class="badge badge-danger py-1 px-2 danger-status">
        <i class="bi bi-x-circle-fill mr-1"></i>
        Not Approved
    </span>
@endif
