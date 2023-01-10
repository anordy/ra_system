@if ($row->status === \App\Models\TaxpayerAmendmentRequest::PENDING)
    <a href="{{ route('kycs-amendment.show', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i> View & Approve
    </a>
@else
    <a href="{{ route('kycs-amendment.show', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i> View
    </a>
@endif