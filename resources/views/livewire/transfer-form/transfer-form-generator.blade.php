<div>
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Select Transfer Bank</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body d-flex justify-content-center">
                <div class="form-group col-lg-8">
                    <label class="control-label">Transfer Bank</label>
                    <select type="text" class="form-control" wire:model.defer="bankAccountId" id="bankAccountId">
                        <option selected>---Select Transfer Bank---</option>
                        @foreach ($zrbBankAccounts as $zrbBankAccount)
                            <option value="{{ $zrbBankAccount->id }}">{{ $zrbBankAccount->bank->name }}</option>
                        @endforeach
                    </select>
                    @error('bankAccountId')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Confirm
                </button>
            </div>
        </div>
    </div>
</div>
