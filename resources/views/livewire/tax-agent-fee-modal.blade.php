<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase text-center">Adding fee configuration for taxagent</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label class="control-label">Taxpayer Nationality</label>
                            <select wire:model.lazy="nationality" name="nationality" id="nationality" class="form-control">
                                <option  value="">select nationality</option>
                                <option value="1">Local</option>
                                <option value="0">Foreigner</option>

                            </select>
                            @error('nationality')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Category</label>
                            <select wire:model.lazy="category" name="category" id="category" class="form-control">
                                <option  value="">select category</option>
                                <option value="Registration Fee">Registration Fee</option>
                                <option value="Renewal Fee">Renewal Fee</option>

                            </select>
                            @error('category')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="control-label">Duration(Years)</label>
                            <select wire:model="duration" class="form-control">
                                <option value="">select duration</option>
                                <option value="2">2 years</option>
                                <option value="3">3 years</option>
                            </select>
                            @error('duration')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="control-label">Amount</label>
                            <input x-data x-mask:dynamic="$money($input)" type="text" class="form-control" wire:model.lazy="amount" id="amount">
                            @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="control-label">Currency</label>
                            <input readonly type="text" class="form-control" wire:model.lazy="currency" id="currency">
                            @error('currency')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
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
                    </div>Save</button>
            </div>
        </div>
    </div>
</div>
