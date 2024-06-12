@if ($row->app_status === \App\Models\BusinessStatus::APPROVED)
    <span class="badge badge-danger py-1 px-2 custom-payment-box">
        <i class="bi bi-record-circle mr-1"></i>
        Approved
    </span>
@elseif($row->app_status === \App\Enum\DisputeStatus::PENDING)
    <span class="badge badge-danger py-1 px-2 pending-status">
        <i class="bi bi-clock-history mr-1"></i>
        Pending
    </span>
@elseif($row->app_status === \App\Enum\DisputeStatus::DRAFT)
    <span class="badge badge-danger py-1 px-2 draft-status">
        <i class="bi bi-pencil-square mr-1"></i>
        Draft
    </span>
@elseif($row->ap_status === \App\Enum\DisputeStatus::CORRECTION)
    <span class="badge badge-danger py-1 px-2 danger-status">
        <i class="bi bi-record-circle mr-1"></i>
        Correction
    </span>
@elseif($row->app_status === \App\Enum\DisputeStatus::REJECTED)
    <span class="badge badge-danger py-1 px-2 danger-status">
        <i class="bi bi-record-circle mr-1"></i>
        Rejected
    </span>
@elseif($row->app_status === \App\Enum\DisputeStatus::SUBMITTED)
    <span class="badge badge-danger py-1 px-2 pending-status">
        <i class="bi bi-record-circle mr-1"></i>
        Submitted
    </span>
@endif
