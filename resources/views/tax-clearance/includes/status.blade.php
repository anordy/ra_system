@if ($value === \App\Enum\TaxClearanceStatus::REQUESTED)
    <span class="badge badge-warning py-1 px-2 text-capitalize pending-status">
        {{ $value }}
    </span>
@elseif ($value === \App\Enum\TaxClearanceStatus::REJECTED)
    <span class="badge badge-danger py-1 px-2 text-capitalize danger-status">
        {{ $value }}
    </span>
@elseif ($value === \App\Enum\TaxClearanceStatus::APPROVED)
    <span class="badge badge-success py-1 px-2 text-capitalize green-status">
        {{ $value }}
    </span>
@endif