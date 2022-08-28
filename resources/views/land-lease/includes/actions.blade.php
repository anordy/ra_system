@can('land-lease-view')
    <a href="{{ route('land-lease.view', encrypt($row->id)) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i> View
    </a>
@endcan
