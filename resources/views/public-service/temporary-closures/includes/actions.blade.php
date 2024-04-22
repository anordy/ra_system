<a href="{{ route('public-service.temporary-closures.show', encrypt($value)) }}" class="btn btn-primary btn-sm">
    <i class="bi bi-eye-fill mr-1"></i>
    @if($row->status == \App\Enum\PublicService\TemporaryClosureStatus::PENDING)
        {{ __('View & Approve') }}
    @else
        {{ __('View Details') }}
    @endif
</a>