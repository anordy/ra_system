@can('land-lease-view')
    <a href="{{ route('land-lease.view', encrypt($value)) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i> View
    </a>
@endcan

@can('land-lease-edit')
<a href="{{ route('land-lease.edit', encrypt($row->id)) }}" class="btn btn-outline-warning btn-sm">
    <i class="bi bi-pencil-square mr-1"></i> {{ __('Edit') }}
</a>
@endcan

@if (!$row->is_registered)
    <a href="{{ route('land-lease.assign.taxpayer', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-person-check-fill mr-1"></i> {{ __('Assign Taxpayer') }}
    </a>
@endif
