<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Edit ZRA Bank Account: {{$zrbBankAccount->account_number}}</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Bank</label>
                        <select type="text" class="form-control" wire:model.defer="bank_id" id="bank_id">
                                <option selected>---Select Bank---</option>
                                @foreach ($banks as $bank)
                                <option {{($bank->id == $zrbBankAccount->bank_id) ? 'selected' : ''}} value="{{ $bank->id }}">{{ $bank->name }}</option>
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
                        <label class="control-label">Swift Code</label>
                        <input type="text" onkeyup="var start = this.selectionStart;var end = this.selectionEnd;this.value = this.value.toUpperCase();this.setSelectionRange(start, end);" class="form-control" wire:model.defer="swift_code" id="swift_code">
                        @error('swift_code')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Currency </label>
                        <select type="text" class="form-control" wire:model.defer="currency" id="currency">
                                <option>---Select Currency---</option>
                                @foreach ($currencies as $currency)
                                    <option {{$zrbBankAccount['currency_id'] == $currency->id ? 'selected' : ''}} value="{{ $currency }}">{{ $currency->iso }} </option>
                                @endforeach
                        </select>
                        @error('currency')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Bank Account Type</label>
                        <select type="text" class="form-control" wire:model.defer="is_transfer" id="is_transfer">
                            <option>---Select Type---</option>
                            <option {{$zrbBankAccount['is_transfer'] == true ? 'selected' : '' }} value="1">Transfer Account</option>
                            <option {{$zrbBankAccount['is_transfer'] == false ? 'selected' : '' }} value="0">Normal Account</option>
                        </select>
                        @error('is_transfer')
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
