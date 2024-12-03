<div class="card">
    <div class="card-body">
        {{-- Nothing in the world is as soft and yielding as water. --}}
        {{-- @include('layouts.component.messages') --}}

        <div>
            <div class="card-header text-uppercase font-weight-bold bg-white pt-1">
                {{ __('Applicant Information') }}
            </div>
            <div class="row pt-3">
                @if ($isBusiness == 1)
                    <div class="col-md-4 form-group">
                        <label for="business_zin" class="d-flex justify-content-between">
                                <span>
                                    {{ __('Business Zin Number') }} *
                                </span>
                        </label>
                        <input id="business_zin" name="businessZin" type="text" wire:model.lazy="businessZin"
                               class="form-control @error('businessZin') is-invalid @enderror">
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

                @else
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
                        {{-- <x-input name="zrbNumber" label="ZRA reference number"></x-input> --}}
                    @else
                        <div class="col-md-4 form-group">
                            <label for="applicant_category" class="d-flex justify-content-between">
                                <span>
                                    {{ __('Category') }} *
                                </span>
                            </label>

                            <select name="applicantCategory" id="applicant_category" wire:model="applicantCategory"
                                    class="form-control {{ $errors->has($applicantCategory) ? 'is-invalid' : '' }}">
                                <option value="">{{ __('Select Category') }}</option>
                                <option value="sole">{{ __('Sole Owner') }}</option>
                                <option value="partnership">{{ __('Partnership') }}</option>
                                <option value="partnership">{{ __('Company') }}</option>
                            </select>
                            @error('applicantCategory')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <x-input name="name" required></x-input>
                        <x-input name="email" required></x-input>
                        <x-input name="phoneNumber" label="{{ __('Phone Number') }}" required></x-input>
                        <x-input name="address" required></x-input>
                    @endif
                @endif
            </div>
        </div>

        <div class="pt-4">
            <div class="card-header text-uppercase font-weight-bold bg-white">
                {{ __('Lease Information') }}
            </div>
            <div class="row pt-3">
                <x-input name="dpNumber" label="DP Number" required></x-input>
                <x-input name="commenceDate" label="Commence Date" type='date' required></x-input>
                <x-input name="rentCommenceDate" label="ZIDRAS Commence Date" type='date' required></x-input>
                <div class="col-md-4 form-group">
                    <label for="applicant_category" class="d-flex justify-content-between'">
                        <span>
                            {{ __('Payment Month')}} *
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

                <x-input name="validPeriodTerm" label="{{ __('Valid Period Term') }}" type="number" required></x-input>
                <x-input name="usedFor" label="{{ __('Lease For') }}" type="text" required></x-input>
                <x-input name="area" label="{{ __('Area') }}" type="number" required></x-input>
                <x-input x-mask:dynamic="$money($input)" name="paymentAmount" label="{{ __('Payment Amount')
                }} (USD)" type="number" required></x-input>
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

            <div class="pt-2 mt-2"></div>

        </div>
        <div>
            <div class="card-header text-uppercase font-weight-bold bg-white">
                {{ __('Lease Documents') }}
            </div>
            <div class="pt-2 mt-2 text-danger"> {{ __('*You can only view the documents but not upload') }}</div>
            @if(!is_null($previousLeaseAgreementPath))
                <div class="row pt-3">
                    @foreach($previousLeaseAgreementPath as $file)
                        <div class="col-md-3 form-group">
                            <a class="file-item" target="_blank"
                               href="{{ route('land-lease.get.lease.document', ['path' => encrypt($file->file_path)]) }}">
                                <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                <div style="font-weight: 500;" class="ml-1">
                                    {{ $file->name }} <small class="text-danger">{{__("(Preview)")}}</small>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="row pt-3">
                        <div class="col-md-3 form-group text-danger">
                           {{__('No documents found')}}
                        </div>
                </div>
            @endif

        </div>

        <div class="row mt-3">
            <div class="col-md-12 d-flex justify-content-end">
                <button class="btn btn-warning ml-1" wire:click="submit" wire:loading.attr="disabled">
                    {{ __('Submit Changes') }}
                    <i class="bi bi-chevron-right ml-1" wire:loading.remove wire:target="submit"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                       wire:target="submit"></i>
                </button>
            </div>
        </div>
    </div>
</div>