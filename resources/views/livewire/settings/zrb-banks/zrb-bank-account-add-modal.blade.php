<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Add Zrb Bank Account</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Bank</label>
                        <select type="text" class="form-control" wire:model.defer="bank_id" id="bank_id">
                                <option selected>---Select Bank---</option>
                                @foreach ($banks as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                            @endforeach
                        </select>
                        @error('bank_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Account Name</label>
                        <input type="text" class="form-control" wire:model.defer="account_name" id="account_name">
                        @error('account_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Account Number</label>
                        <input type="number" class="form-control" wire:model.defer="account_number" id="account_number">
                        @error('account_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Branch Name</label>
                        <input type="text" class="form-control" wire:model.defer="branch_name" id="branch_name">
                        @error('branch_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Currency</label>
                        <select type="text" class="form-control" wire:model.defer="currency_id" id="currency_id">
                                <option selected>---Select Currency---</option>
                                @foreach ($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->iso }}</option>
                            @endforeach
                        </select>
                        @error('currency_id')
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
