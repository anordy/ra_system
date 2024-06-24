@if($value === \App\Enum\TaxVerificationStatus::PENDING || $value == \App\Enum\TaxVerificationStatus::CORRECTION)
    <a href="{{ route('tax_verifications.edit', encrypt($value)) }}" class="btn btn-primary btn-sm" data-toggle="tooltip"
       data-placement="right" title="View">
        <i class="bi bi-eye-fill mr-1"></i>
        Review & Approve
    </a>
@else
    <a href="{{ route('tax_verifications.show', encrypt($value)) }}" class="btn btn-primary btn-sm" data-toggle="tooltip"
       data-placement="right" title="View">
        <i class="bi bi-eye-fill mr-1"></i>
        View Verification
    </a>
@endif