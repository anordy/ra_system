<div>
    @can('tax-consultant-registration-verify')
    <button type="button" class="btn btn-primary ml-2 px-3" wire:click='approve' wire:loading.attr="disabled">
        <div wire:loading.delay wire:target="approve">
            <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <i class="bi bi-check-circle-fill"></i> Verify
    </button>
    @endcan

        @can('tax-consultant-registration-reject-first')
            <button type="button" class="btn btn-danger ml-2 px-3" wire:click='reject' wire:loading.attr="disabled">
                <div wire:loading.delay wire:target="reject">
                    <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <i class="bi bi-x-circle-fill"></i> Reject
            </button>
        @endcan
</div>
