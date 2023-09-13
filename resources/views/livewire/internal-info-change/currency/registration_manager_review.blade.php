
<div class="card rounded-0 shadow-none border">
    <div class="card-body">
        <div class="row pr-3 pl-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Current Currency</label>
                    <select class="form-control @error('currentCurrencyId') is-invalid @enderror"
                            wire:model.defer="currentCurrencyId" disabled>
                        <option value="null" disabled selected>Select</option>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                        @endforeach
                    </select>
                    @error('currentCurrencyId')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">New Currency</label>
                    <select class="form-control @error('businessCurrencyId') is-invalid @enderror"
                            wire:model.defer="businessCurrencyId">
                        <option value="null" disabled selected>Select</option>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                        @endforeach
                    </select>
                    @error('taxRegionId')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>

    </div>
</div>