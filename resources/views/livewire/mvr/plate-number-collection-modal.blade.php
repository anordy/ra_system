<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Plate Number Collection</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Collection Date</label>
                        <input type="date" class="form-control" wire:model.lazy="collection_date" id="collection_date">
                        @error('collection_date')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Collector Full Name</label>
                        <input type="text" class="form-control" wire:model.lazy="collector_name" id="collector_name">
                        @error('collector_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Collector Phone Number</label>
                        <input type="text" class="form-control" wire:model.lazy="collector_phone" id="collector_phone">
                        @error('collector_phone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button wire:click="submit" wire:loading.attr="disabled" class="btn btn-primary">
                    <div wire:loading wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Submit
                </button>
            </div>
        </div>
    </div>
</div>
