<div class="card">
    <div class="card-body">
        <div class="col-md-4 form-group">
            <label for="zrb_number" class="d-flex justify-content-between">
                <span>
                    {{ __('ZRA reference number') }}
                </span>
            </label>
            <input id="zrb_number" name="zrbNumber" type="text" wire:model.lazy="zrbNumber"
                class="form-control @error('zrbNumber') is-invalid @enderror">
            @error('zrbNumber')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            @if ($showTaxpayerDetails)
                <div class="d-flex justify-content-between">
                    {{ __('Name') }} : {{ $taxpayerName }}
                </div>
            @endif
        </div>
    </div>

    <div class="card-body">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            {{__('Lease Information')}}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Site Plan Number') }} (DP No.)</span>
                    <p class="my-1">{{ $landLease->dp_number }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Region') }}</span>
                    <p class="my-1">{{ $landLease->region->name }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('District') }}</span>
                    <p class="my-1">{{ $landLease->district->name }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Ward') }}</span>
                    <p class="my-1">{{ $landLease->ward->name }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Commence Date') }}</span>
                    <p class="my-1">{{ date('d/m/Y', strtotime($landLease->commence_date)) }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Payment Month') }}</span>
                    <p class="my-1">{{ $landLease->payment_month }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Payment Amount') }}</span>
                    <p class="my-1">{{ number_format($landLease->payment_amount) }} USD</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Review Schedule') }}</span>
                    <p class="my-1">{{ $landLease->review_schedule }} years</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Valid Period Term') }}</span>
                    <p class="my-1">{{ $landLease->valid_period_term }} {{ __('years') }}</p>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12 d-flex justify-content-end">
                <button class="btn btn-primary ml-1" wire:click="submit" wire:loading.attr="disabled">
                    {{ __('Assign Taxpayer') }}
                    <i class="bi bi-chevron-right ml-1" wire:loading.remove wire:target="submit"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                        wire:target="submit"></i>
                </button>
            </div>
        </div>
    </div>

</div>
