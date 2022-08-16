<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Search Motor Vehicle by Chassis</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Type of Search</label>
                        <select type="radio" class="form-control" wire:model.lazy="type" id="type">
                            <option>Choose search type</option>
                            <option value="chassis">Chassis Number</option>
                            <option value="plate-number">Plate Number</option>
                        </select>
                        @error('type')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">{{ucwords(preg_replace('/-/',' ',$type))}} Number</label>
                        <input type="text" class="form-control" wire:model.lazy="number" id="number">
                        @error('number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Search</button>
            </div>
        </div>
    </div>
</div>
