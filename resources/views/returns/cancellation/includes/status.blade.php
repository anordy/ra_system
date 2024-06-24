@if($row->status === \App\Enum\TaxVerificationStatus::PENDING)
    <span class="badge badge-warning py-1 px-2">
        <i class="bi bi-clock-history mr-1"></i>
        Pending
    </span>
@elseif($row->status === \App\Enum\TaxVerificationStatus::APPROVED)
    <span class="badge badge-success py-1 px-2">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Approved
    </span>
@elseif($row->status === \App\Enum\TaxVerificationStatus::REJECTED)
    <span class="badge badge-danger py-1 px-2">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Rejected
    </span>
@else
    <span class="badge badge-danger py-1 px-2">
        <i class="bi bi-x-circle-fill mr-1"></i>
        {{ $row->status }}
    </span>
@endif