@if ($row->status === \App\Models\BranchStatus::PENDING)
    <a href="{{ route('business.branches.show', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i> View & Approve
    </a>
@else
    <a href="{{ route('business.branches.show', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i> View
    </a>
@endif

@if(!$row->vfms_associated_at)
    @can('vfms-business-unit-data-linking')
        <button class="m-1 btn btn-outline-success rounded-0 btn-sm" onclick="Livewire.emit('showModal', 'business.vfms.fetch-business-unit-data-modal', '{{  encrypt($row->id) }}', '{{ false }}')">
            <i class="bi bi-gear-wide-connected mr-1"></i> Vfms Integration
        </button>
    @endcan
@else
    @can('vfms-business-unit-update')
        <button class="m-1 btn btn-outline-success rounded-0 btn-sm" onclick="Livewire.emit('showModal', 'business.vfms.update-business-unit-details', '{{  encrypt($row->id) }}', '{{ false }}')">
            <i class="bi bi-pen mr-1"></i> Update Business Units
        </button>
    @endcan
@endif

