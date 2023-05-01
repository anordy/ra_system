<div>
    <div class="row mx-4 mt-2">

        <div class="form-group col-md-4">
            <label>Reconcilliation Type</label>
            <select wire:model="recon_type" class="form-control">
                <option selected>Select Reconcilliation Type</option>
                <option value="1">ZanMalipo Successful Transactions</option>
                <option value="2">Exception Transaction report after reconciliation between ZanMalipo and payment service provider</option>
            </select>
            @error('recon_type')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group col-md-4">
            <label class="control-label">Transaction Date</label>
            <input type="date" max="{{ $today }}" class="form-control" wire:model.lazy="transaction_date" id="transaction_date">
            @error('transaction_date')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group col-md-3 mt-4">
            <button type="button" class="btn btn-primary" wire:click='triggerAction' wire:loading.attr="disabled">
                <div wire:loading.delay wire:target="triggerAction">
                    <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                Submit Reconcilliation Request
            </button>
        </div>
    </div>
</div>
