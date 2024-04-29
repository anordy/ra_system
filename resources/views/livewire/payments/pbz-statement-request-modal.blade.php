<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-uppercase">New Bank Statement Request</h6>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                <x-errors></x-errors>
                <div class="row mx-0">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold">Account No.</label>
                            <select class="form-control @error('bankAccount') is-invalid @enderror" wire:model="bankAccount" >
                                <option>Please select bank account</option>
                                @foreach($bankAccounts as $account)
                                    <option value="{{ $account->id  }}">{{ $account->account_number }} {{ $account->account_name ? ' - ' . $account->account_name : '' }} ({{ $account->currency }})</option>
                                @endforeach
                            </select>
                            @error('bankAccount')
                            <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Statement Date</label>
                            <input wire:model="statementDate"
                                   class="form-control @error('statementDate') is-invalid @enderror"
                                   type="date" max="{{ \Carbon\Carbon::yesterday()->toDateString() }}">
                            @error('statementDate')
                            <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @else
                                <span class="small mt-2 d-block">
                                    Request bank statements of up to {{ \Carbon\Carbon::yesterday()->toFormattedDateString() }}.
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary mr-2" wire:click="submit" wire:loading.attr="disable">
                    <i class="bi bi-forward mr-2" wire:loading.remove wire:target="submit"></i>
                    <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading wire:target="submit"></i>
                    Send Statement Request
                </button>
            </div>
        </div>
    </div>
</div>