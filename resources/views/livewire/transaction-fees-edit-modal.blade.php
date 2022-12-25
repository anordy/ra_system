<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Edit Transaction Fee Rate</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Minimum Amount</label>
                        <input type="text" class="form-control" wire:model.lazy="min_amount" id="min_amount">
                        @error('min_amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Maximum Amount </label>
                        <input type="text" class="form-control" wire:model.lazy="max_amount" id="max_amount">
                        @error('max_amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    <small>*leave empty if there is no limit on maximum amount</small>
                    </div>
                </div>
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Fee</label>
                        <input type="text" class="form-control" wire:model.lazy="fee" id="fee">
                        @error('fee')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div><br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Update changes</button>
            </div>
        </div>
    </div>
</div>
