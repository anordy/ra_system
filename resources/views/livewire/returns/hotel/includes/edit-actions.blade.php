<div class="col-md-12 text-right">
    <a wire:click='toggleSummary(false)' class="btn btn-danger mr-2">Back</a>
    <button type="button" class="btn btn-primary ml-2 px-5" wire:click='submit()' wire:loading.attr="disabled">
        <div wire:loading wire:target="submit()">
            <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        Submit Adjustment</button>
</div>