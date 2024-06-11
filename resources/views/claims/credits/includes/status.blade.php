@if ($row->status === \App\Enum\TaxClaimStatus::APPROVED)
    <span class="badge badge-success py-1 px-2 green-status">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Approved
        </span>
@elseif($row->status === \App\Enum\TaxClaimStatus::PENDING)
    <span class="badge badge-danger py-1 px-2 draft-status">
        <i class="bi bi-clock-history mr-1"></i>
        Pending
    </span>
@elseif($row->status === \App\Enum\TaxClaimStatus::DRAFT)
    <span class="badge badge-danger py-1 px-2 pending-status">
        <i class="bi bi-pencil-square mr-1"></i>
        Draft
    </span>
@elseif($row->status === \App\Enum\TaxClaimStatus::CORRECTION)
    <span class="badge badge-danger py-1 px-2 danger-status">
        <i class="bi bi-record-circle mr-1"></i>
        Correction
    </span>
@endif
