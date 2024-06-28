<div class="p-3">
    <h3>{{ __('Offence Approve') }}</h3>
    <p>{{ __('Please provide all the required information to continue') }}.</p>
    <hr/>
    <div class="row">

        <div class="form-group col-md-12">
            <label class="font-weight-bold">{{ __('Debtor name') }} *</label>
            <textarea class="form-control" wire:model.lazy="comment" rows="3" required>

            </textarea>
            @error('comment')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-md-12 text-right">
            <button class="btn btn-primary rounded-0" wire:click="submit" wire:loading.attr="disable">
                <i class="bi bi-arrow-return-right mr-2" wire:loading.remove wire:target="submit"></i>
                <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading wire:target="submit"></i>
                {{ __('Submit') }}
            </button>
        </div>
    </div>


</div>