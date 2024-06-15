@if($status === \App\Enum\InternalInfoChangeStatus::APPROVED)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Approved
    </span>
@elseif($status === \App\Enum\InternalInfoChangeStatus::PENDING)
    <span class="badge badge-danger py-1 px-2 pending-status">
        <i class="bi bi-clock-history mr-1"></i>
        Pending
    </span>
@endif