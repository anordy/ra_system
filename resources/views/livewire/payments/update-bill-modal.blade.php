<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="text-uppercase">Update Bill</h5>
        </div>
        <div class="modal-body">
            <div class="border-0">
                <div class="row mx-4 mt-2">
                    <div class="form-group col-md-12">
                        <label class="control-label">Current Expiration Date</label>
                        <input type="date" class="form-control" disabled wire:model.lazy="current_expiration_date" id="current_expiration_date">
                        @error('current_expiration_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label">New Expiration Date</label>
                        <input type="date" class="form-control" wire:model.lazy="new_expiration_date" id="new_expiration_date">
                        @error('new_expiration_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- <div class="col-md-12 form-group">
                        <label for="extension_reason">Extension Reason</label>
                        <textarea class="form-control @error('extension_reason') is-invalid @enderror" wire:model.lazy='extension_reason' rows="3"></textarea>
                        @error('extension_reason')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div> --}}
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
