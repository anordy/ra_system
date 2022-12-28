<div>
    <button type="button" class="btn btn-danger mr-1 px-4" wire:click="confirmPopUpModal('reject')"
            wire:loading.attr="disabled">
        <i class="bi bi-x-circle mr-1" wire:loading.remove wire:target="reject"></i>
        <i class="spinner-border spinner-border-sm mr-1" role="status" wire:loading
           wire:target="reject"></i>
        Reject
    </button>

    <button type="button" class="btn btn-success mr-1 px-4" wire:click="confirmPopUpModal('approve')"
            wire:loading.attr="disabled">
        <i class="bi bi-check-lg mr-1" wire:loading.remove wire:target="approve"></i>
        <i class="spinner-border spinner-border-sm mr-1" role="status" wire:loading
           wire:target="approve"></i>
        Approve
    </button>

</div>
