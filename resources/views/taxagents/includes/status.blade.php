@if($row->status == \App\Models\TaxAgentStatus::PENDING)
    <span class="badge badge-danger py-1 px-2 danger-status">
        <i class="bi bi-clock-history mr-1"></i>
        Pending
    </span>

@elseif($row->status == \App\Models\TaxAgentStatus::APPROVED)
        <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Approved
    </span>
@elseif($row->status == \App\Models\TaxAgentStatus::VERIFIED)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Verified
    </span>
@elseif($row->status == \App\Models\TaxAgentStatus::CORRECTION)
    <span class="badge badge-success py-1 px-2 danger-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Corrected
    </span>
@else
    <span class="badge badge-danger py-1 px-2 danger-status">
        <i class="bi bi-x-circle-fill mr-1"></i>
        Rejected
    </span>
@endif