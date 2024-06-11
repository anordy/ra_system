<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Approve Ownership Transfer</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h6>Confirm Ownership Transfer Category</h6>
                    </div>
                </div>
                <br>
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Transfer Category</label>
                        <select class="form-control" wire:model.lazy="transfer_category_id" id="transfer_category_id">
                            <option value="" selected>Choose option</option>
                            @foreach (\App\Models\MvrTransferCategory::query()->get() as $row)
                                <option value="{{$row->id}}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        @error('transfer_category_id')
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
