<div>
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">{{ __('Select Transfer Bank') }}</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body d-flex justify-content-center">
                <div class="row">
                <div class="form-group col-lg-12">
                    <label class="control-label">{{ __('Transfer Bank') }}</label>
                    <select type="text" class="form-control" wire:model.defer="bankAccountId" id="bankAccountId">
                        <option selected>---{{ __('Select Transfer Bank') }}---</option>
                        @foreach ($zrbBankAccounts as $zrbBankAccount)
                            <option value="{{ $zrbBankAccount->id }}">{{ $zrbBankAccount->bank->name ?? '' }}</option>
                        @endforeach
                    </select>
                    @error('bankAccountId')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label">Do you Want to to use your saved Bank Accounts?</label>
                    <select type="text" class="form-control" wire:model.lazy="useSavedBankAccounts" id="useSavedBankAccounts">
                        <option selected>---Select---</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    @error('useSavedBankAccounts')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if ($useSavedBankAccounts)
                    <div class="form-group col-lg-12">
                        <label class="control-label">Business Bank Account (Transferer Account)</label>
                        <select type="text" class="form-control" wire:model.defer="businessBankAccId" id="businessBankAccId">
                            <option selected>---Select Transferer Account---</option>
                            @foreach ($businessBanks as $businessBank)
                                <option value="{{ $businessBank->id }}">{{ $businessBank->acc_no ?? '' }} - {{ $businessBank->branch ?? '' }}</option>
                            @endforeach
                        </select>
                        @error('businessBankAccId')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>{{__('Confirm')}}
                </button>
            </div>
        </div>
    </div>
</div>
