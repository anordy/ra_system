<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Edit Exchange Rate</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="row pr-3 pl-3">

                        <div class="form-group col-lg-12">
                            <label class="control-label">Spot Buying</label>
                            <input type="number" step="0.02" class="form-control" wire:model.lazy="spot_buying"
                                id="spot_buying">
                            @error('spot_buying')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label">Spot Selling</label>
                            <input type="number" step="0.02" class="form-control" wire:model.lazy="spot_selling"
                                id="spot_selling">
                            @error('spot_selling')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label">Mean</label>
                            <input type="number" step="0.02" class="form-control" wire:model.lazy="mean"
                                id="mean">
                            @error('mean')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label">Exchange Date</label>
                            <input type="date" class="form-control" wire:model.lazy="exchange_date"
                                id="exchange_date">
                            @error('exchange_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Update changes</button>
            </div>
        </div>
    </div>
</div>
