<a href="{{ route('land-lease.view', encrypt($row['landlease.id'])) }}" class="btn btn-outline-secondary btn-sm">
    <i class="bi bi-eye-fill mr-1"></i> {{ __('View') }}
</a>

{{--TODO: approval/rejection should have permissions--}}

@if($row->status == 'pending')
    <button wire:click="approve({{ $row->id }})"
            class="btn btn-outline-success btn-sm"
    >
        <i class="bi bi-check-circle-fill mr-1"></i> {{ __('Approve') }}
    </button>
    <button wire:click="reject({{ $row->id }})"
            class="btn btn-outline-danger btn-sm"
    >
        <i class="bi bi-x-circle-fill mr-1"></i> {{ __('Reject') }}
    </button>
@endif

