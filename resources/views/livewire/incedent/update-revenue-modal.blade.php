<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Revenue Leakage</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-md-12">
                        <label class="control-label">Type</label>
                        <select class="form-control" wire:model.lazy="type" id="type">
                            <option>--select--</option>
                            <option value="Revenue Loss">Revenue Loss</option>
                            <option value="Overcharging">Overcharging</option>
                        </select>
                        @error('type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group col-md-12">
                        <label class="control-label">Currency</label>
                        <select class="form-control" wire:model.lazy="currency" id="currency">
                            <option>--select--</option>
                            @foreach ($currencies as $row)
                                <option value="{{ $row->code }}">{{ $row->code }}
                                </option>
                            @endforeach
                        </select>
                        @error('currency')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Detected</label>
                        <input type="text" class="form-control" wire:model.defer="detected"
                               id="detected">
                        @error('detected')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label"> Total Detected</label>
                        <input type="text" class="form-control" readonly wire:model.defer="detected"
                               id="detected">
                        @error('detected')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Prevented</label>
                        <input type="text" class="form-control" wire:model.defer="prevented"
                               id="prevented">
                        @error('prevented')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Total Prevented</label>
                        <input type="text" class="form-control" readonly wire:model.defer="prevented"
                               id="prevented">
                        @error('prevented')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Recovery</label>
                        <input type="text" class="form-control"  wire:model.defer="recovery"
                               id="recovery">
                        @error('recovery')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Total Recovery</label>
                        <input type="text" class="form-control" readonly wire:model.defer="prevented"
                               id="prevented">
                        @error('prevented')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Submit</button>
            </div>
        </div>
    </div>
</div>
