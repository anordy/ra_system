<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Add Reorder Fee</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group col-lg-12">
                    <label class="control-label">Quantity</label>
                    <select class="form-control" wire:model.lazy="quantity" id="quantity">
                        <option value="" selected>Choose option</option>
                        <option value="SINGLE">Single Plate</option>
                        <option value="BOTH">Both Plate</option>
                    </select>
                    @error('is_rfid')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group col-lg-12">
                    <label class="control-label">RFID</label>
                    <select class="form-control" wire:model.lazy="is_rfid" id="is_rfid">
                        <option value="" selected>Choose option</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    @error('is_rfid')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-lg-12">
                    <label class="control-label">Plate Sticker</label>
                    <select class="form-control" wire:model.lazy="is_plate_sticker" id="is_plate_sticker">
                        <option value="" selected>Choose option</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    @error('is_plate_sticker')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Amount</label>
                        <input type="text" class="form-control" wire:model.lazy="amount" id="amount">
                        @error('amount')
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
                    </div>Save changes
                </button>
            </div>
        </div>
    </div>
</div>
