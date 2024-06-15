@if ($row->status === \App\Enum\InstallmentStatus::ACTIVE)
    <span class="badge badge-success py-1 px-2 draft-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Active
    </span>
@elseif($row->status === \App\Enum\InstallmentStatus::COMPLETE)
    <span class="badge badge-danger py-1 px-2 green-status">
        <i class="bi bi-clock-history mr-1"></i>
        Complete
    </span>
@elseif($row->status === \App\Enum\InstallmentStatus::CANCELLED)
    <span class="badge badge-danger py-1 px-2 danger-status">
        <i class="bi bi-record-circle mr-1"></i>
        Cancelled
    </span>
@endif

