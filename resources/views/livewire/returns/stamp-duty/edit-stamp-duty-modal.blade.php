<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Edit Stamp Duty Service</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Name</label>
                        <input type="text" class="form-control" wire:model.lazy="name">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Code</label>
                        <input type="text" class="form-control" wire:model.lazy="code">
                        @error('code')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Rate Type</label>
                        <select wire:model="rate_type">
                            <option></option>
                            <option value="fixed">Fixed</option>
                            <option value="percent"> Percentage</option>
                        </select>
                        @error('description')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Rate</label>
                        <input type="number" class="form-control" wire:model.lazy="rate">
                        @error('rate')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Is Active</label>
                        <select wire:model="rate_type">
                            <option></option>
                            <option value="fixed">Fixed</option>
                            <option value="percent"> Percentage</option>
                        </select>
                        @error('description')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Financial Year</label>
                        <select wire:model="financial_year">
                            <option></option>
                            <option value="fixed">Fixed</option>
                            <option value="percent"> Percentage</option>
                        </select>
                        @error('description')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Is File Required</label>
                        <select wire:model.lazy="is_required" class="form-control">
                            <option></option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        @error('is_required')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">File Type</label>
                        <select class="form-control">
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Save changes</button>
            </div>
        </div>
    </div>
</div>
