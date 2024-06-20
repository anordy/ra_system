<div>
    <div>
        <div class="card-header text-uppercase font-weight-bold bg-white pt-1">
            {{ __('Applicant Information') }}
        </div>

        <div class="row pt-3">
            <div class="col-md-4 form-group">
                <label for="applicant_category" class="d-flex justify-content-between'">
                    <span>
                        {{ __('Lease For') }}: *
                    </span>
                </label>
                <select name="isBusiness" id="applicant_category" wire:model="isBusiness" class="form-control">
                    <option value=1>{{ __('Business') }}</option>
                    <option value=0>{{ __('Sole Owner') }}</option>
                </select>
            </div>
        </div>
        @if ($isBusiness == 1)
            <div class="row pt-3">
                <div class="col-md-4 form-group">
                    <label for="business_zin" class="d-flex justify-content-between">
                        <span>
                            {{ __('Business Zin Number') }} *
                        </span>
                    </label>
                    <input id="business_zin" name="businessZin" type="text" wire:model.lazy="businessZin"
                           class="form-control @error('businessZin') is-invalid @enderror">
                    <p class="pt-1 text-secondary font-italic">
                        {{ __('e.g') }} ( Z011100001-010 )
                    </p>
                    @error('businessZin')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                    @if ($showBusinessDetails)
                        <div class="d-flex justify-content-between">
                            {{ __('Name') }} : {{ $businessName }}
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="row pt-3">
                <div class="col-md-4 form-group">
                    <label for="applicant_type" class="d-flex justify-content-between'">
                        <span>
                            {{ __('Applicant Type') }}
                        </span>
                    </label>

                    <select name="applicantType" id="applicant_type" wire:model="applicantType" class="form-control">
                        <option value="registered">{{ __('Registered Applicant') }}</option>
                        <option value="unregistered">{{ __('Un-Registered Applicant') }}</option>
                    </select>
                </div>
                @if ($applicantType == 'registered')
                    <div class="col-md-4 form-group">
                        <label for="zrb_number" class="d-flex justify-content-between">
                            <span>
                                {{ __('ZRA reference number') }} *
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
                @else
                    <x-input name="name" required></x-input>
                    <x-input name="email" required></x-input>
                    <x-input name="phoneNumber" label="{{ __('Phone Number') }}" required></x-input>
                    <x-input name="address" required></x-input>
                @endif
            </div>
        @endif

    </div>

    <div class="pt-4">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            {{ __('Lease Information') }}
        </div>
        <div class="row pt-3">
            <x-input name="dpNumber" label="DP Number" required></x-input>
            <x-input name="commenceDate" label="Commence Date" type='date' required></x-input>
            <x-input name="rentCommenceDate" label="Rent Commence Date" type='date' required></x-input>
            <div class="col-md-4 form-group">
                <label for="applicant_category" class="d-flex justify-content-between'">
                    <span>
                        {{ __('Payment Month') }} *
                    </span>
                </label>
                <select name="paymentMonth" id="payment_month" wire:model.lazy="paymentMonth"
                        class="form-control {{ $errors->has($paymentMonth) ? 'is-invalid' : '' }}">
                    <option value="">{{ __('Select Payment Month') }}</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
                @error('paymentMonth')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-md-4 form-group">
                <label for="valid_period_term" class="d-flex justify-content-between'">
                    <span>
                        {{ __('Valid Period Term') }} *
                    </span>
                </label>
                <select name="validPeriodTerm" id="valid_period_term" wire:model="validPeriodTerm"
                        class="form-control {{ $errors->has($validPeriodTerm) ? 'is-invalid' : '' }}">
                    <option value="">{{ __('Select Valid Period Term') }}</option>
                    <option value="33">{{ __('33 Years') }}</option>
                    <option value="other">{{ __('Other') }}</option>
                </select>
                @error($validPeriodTerm)
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            @if ($validPeriodTerm == 'other')
                <x-input name="customPeriod" type="number" label="{{ __('Specify Valid Period') }} (Years) *"></x-input>
            @endif

            <x-input name="paymentAmount" type="number" label="{{ __('Payment Amount') }} (USD)" required></x-input>
            <div class="col-md-4 form-group">
                <label>{{ __('Region') }} *</label>
                <select wire:model.lazy="region" class="form-control @error('region') is-invalid @enderror">
                    <option></option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </select>
                @error('region')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('District') }} *</label>
                <select wire:model.lazy="district" class="form-control @error('district') is-invalid @enderror">
                    <option></option>
                    <option wire:loading wire:target="region">
                        Loading...
                    </option>
                    @foreach ($districts as $district)
                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                    @endforeach
                </select>
                @error('district')
                <div class="invalid-feedback">
                    {{ $errors->first('district') }}
                </div>
                @enderror
            </div>
            <div class="col-md-4 form-group">
                <label><span>{{ __('Ward') }} *</span></label>
                <select wire:model.lazy="ward" class="form-control @error('ward') is-invalid @enderror">
                    <option></option>
                    <option wire:loading wire:target="district">
                        Loading...
                    </option>
                    @foreach ($wards as $ward)
                        <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                    @endforeach
                </select>
                @error('ward')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <button class="btn btn-primary ml-1" wire:click="submit" wire:loading.attr="disabled">
                {{ __('Submit') }}
                <i class="bi bi-chevron-right ml-1" wire:loading.remove wire:target="submit"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                   wire:target="submit"></i>
            </button>
        </div>
    </div>
</div>
