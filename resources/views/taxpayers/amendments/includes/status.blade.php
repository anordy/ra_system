@if ($amendmentRequest->status === \App\Models\TaxpayerAmendmentRequest::APPROVED)
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Approved
    </span>
@elseif ($amendmentRequest->status === \App\Models\TaxpayerAmendmentRequest::PENDING)
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #b56509; color: #fbe577; font-size: 85%">
        <i class="bi bi-hourglass-bottom"></i>
        Requested
    </span>
@elseif($amendmentRequest->status === \App\Models\TaxpayerAmendmentRequest::REJECTED)
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%">
        <i class="bi bi-x-circle mr-1"></i>
        Rejected
    </span>
@else
    <span class="badge badge-danger py-1 px-2"
        style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%">
        <i class="bi bi-x-circle-fill mr-1"></i>
        Not Approved
    </span>
@endif
