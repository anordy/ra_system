<div class="col-md-12 text-right px-0">
    <button type="button" class="btn btn-warning ml-2 px-4" wire:click='toggleSummary(false)' wire:loading.attr="disabled">
        <div wire:loading wire:target="submit('submitted')">
            <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <i class="bi bi-chevron-left mr-2"></i>
        Back
    </button>
    <button type="button" class="btn btn-primary ml-2 px-4" wire:click='submit()' wire:loading.attr="disabled">
        <div wire:loading wire:target="submit()">
            <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <i class="bi bi-arrow-return-right mr-2"></i>
        Submit Return
    </button>
</div>