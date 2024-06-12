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

@can('land-lease-change-status')
    @if($row->lease_status == 1)
        <button wire:click="deactivate({{ $row->id }}, {{ $row->is_registered }})"
                class="btn btn-outline-danger btn-sm"
        >
            <i class="bi bi-lock-fill mr-1"></i> {{ __('Deactivate') }}
        </button>
    @else
        <button wire:click="activate({{ $row->id }}, {{ $row->is_registered }})"
                class="btn btn-outline-success btn-sm"
        >
            <i class="bi bi-unlock-fill mr-1"></i> {{ __('Activate') }}
        </button>
    @endif
@endcan
