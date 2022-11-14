<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="text-uppercase">Cancel Bill</h5>
        </div>
        <div class="modal-body">
            <div class="border-0">
                <div class="row mx-4 mt-2">
                    <div class="col-md-12 form-group">
                        <label for="cancellation_reason">Cancellation Reason</label>
                        <textarea class="form-control @error('cancellation_reason') is-invalid @enderror" wire:model.lazy='cancellation_reason' rows="3"></textarea>
                        @error('cancellation_reason')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
                <div wire:loading wire:target="submit">
                    <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                Submit
            </button>
        </div>
    </div>
</div>
