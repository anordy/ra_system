<a href="{{ route('business.branches.show', encrypt($value)) }}" class="btn btn-outline-primary btn-sm">
    <i class="bi bi-eye-fill mr-1"></i> View More
</a>
@if($row->vfms_associated_at)
    <button class="m-1 btn btn-outline-success rounded-0 btn-sm" onclick="Livewire.emit('showModal', 'business.vfms.fetch-business-unit-data-modal', '{{  encrypt($row->id) }}')">
        <i class="bi bi-pen mr-1"></i> Vfms Integration
    </button>
@endif