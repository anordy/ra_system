@can('land-lease-view')
    <a href="{{ route('land-lease.view', encrypt($value)) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i> View
    </a>
@endcan
