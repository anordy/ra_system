<a href="{{ route('public-service.de-registrations.show', encrypt($value)) }}" class="btn btn-primary btn-sm">
    <i class="bi bi-eye-fill mr-1"></i>
    @if($row->status == \App\Enum\PublicService\DeRegistrationStatus::PENDING)
        {{ __('View & Approve') }}
    @else
        {{ __('View Details') }}
    @endif
</a>