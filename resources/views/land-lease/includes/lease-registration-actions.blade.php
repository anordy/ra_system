@can('land-lease-approve-registration')
    <a href="{{ route('land-lease.registration.view', encrypt($row->id)) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i> {{ __('View') }}
    </a>
@endcan

@if($row->approval_status == 'approved')
    @if(is_null($row->completed_at))
        @can('land-lease-create')
            <a href="{{ route('land-lease.complete.registration', encrypt($row->id)) }}"
               class="btn btn-outline-primary btn-sm">
                {{ __('Complete Registration') }} <i class="bi bi-caret-right-fill"></i>
            </a>
        @endcan
    @endif
@endif
