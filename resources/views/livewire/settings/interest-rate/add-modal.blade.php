<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Add Interest Rate</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Year</label>
                        <input type="text" class="form-control" wire:model.lazy="year" id="year">
                        @error('year')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Rate</label>
                        <input type="text" class="form-control" wire:model.lazy="rate" id="rate">
                        @error('rate')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="submit">
                    <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>Save changes</button>
            </div>
        </div>
    </div>
</div>
