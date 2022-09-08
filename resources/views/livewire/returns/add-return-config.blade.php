<div>
    <div class="row">
        <div class="col-md-4 mb-2">
            <label>Financial Year</label>
            <input readonly value="{{$year->code}}" type="text" class="form-control form-control-lg">
        </div>
        @if($code == \App\Models\TaxType::VAT)
        <div class="col-md-4 mb-2">
            <label>Vat Service</label>
            <select class="form-control {{ $errors->first('service_code') ? 'is-invalid' : '' }}" wire:model="service_code">
                <option value="">--Choose service--</option>
                <option value="SUP">Supplies of goods & services</option>
                <option value="PUR">Purchases (Inputs)</option>
            </select>
            @error('service_code')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        @endif
        <div class="col-md-4 mb-2">
            <label>Name</label>
            <input type="text" class="form-control form-control-lg {{ $errors->first('name') ? 'is-invalid' : '' }}" wire:model="name">
            @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        @if(!empty($name))
        <div class="col-md-4 mb-2">
            <label>Code</label>
            <input readonly type="text" class="form-control form-control-lg {{ $errors->first('config_code') ? 'is-invalid' : '' }}" wire:model="config_code">
            @error('config_code')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        @endif
        <div class="col-md-4 mb-2">
            <label>Row Type</label>
            <select class="form-control {{ $errors->first('row_type') ? 'is-invalid' : '' }}" wire:model="row_type">
                <option value="">--Choose row type--</option>
                <option value="dynamic">Dynamic</option>
                <option value="unremovable">Un-removable</option>
            </select>
            @error('row_type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4 mb-2">
            <label>Column Type</label>
            <select class="form-control {{ $errors->first('col_type') ? 'is-invalid' : '' }}" wire:model="col_type">
                <option>--Choose column type--</option>
                <option value="normal">Normal</option>
                <option value="external">external</option>
                @if($code == \App\Models\TaxType::VAT)
                <option value="exemptedMethodOne">exemptedMethodOne</option>
                <option value="exemptedMethodTwo">exemptedMethodTwo</option>
                @endif
                <option value="subtotal">Subtotal</option>
                <option value="total">Total</option>
                <option value="grandTotal">Grand Total</option>
            </select>
            @error('col_type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-4 mb-2">
            <label>Is Value Calculated?</label>
            <select class="form-control {{ $errors->first('value_calculated') ? 'is-invalid' : '' }}" wire:model="value_calculated">
                <option value="">--Choose--</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
            @error('value_calculated')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4 mb-2">
            <label>Is Rate Applicable?</label>
            <select class="form-control {{ $errors->first('rate_applicable') ? 'is-invalid' : '' }}" wire:model="rate_applicable">
                <option value="">--Choose--</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
            @error('rate_applicable')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4 mb-2">
            <label>Rate Type</label>
            <select class="form-control {{ $errors->first('rate_type') ? 'is-invalid' : '' }}" wire:model="rate_type">
                <option value="">--Choose rate type--</option>
                <option value="fixed">Fixed</option>
                <option value="percentage">Percentage</option>
            </select>
            @error('rate_type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-4 mb-2">
            <label>Rate</label>
            <input type="text" class="form-control form-control-lg {{ $errors->first('rate') ? 'is-invalid' : '' }}" wire:model="rate">
            @error('rate')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-4 mb-2">
            <label>Currency</label>
            <select class="form-control {{ $errors->first('currency') ? 'is-invalid' : '' }}" wire:model="currency">
                <option value="">--Choose currency--</option>
                @foreach($currencies as $currency)
                    <option value="{{$currency->iso}}">{{$currency->name}}</option>
                @endforeach
                <option value="BOTH">Both</option>
            </select>
            @error('currency')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4 mb-2">
            <label>Rate USD</label>
            <input type="text" class="form-control form-control-lg {{ $errors->first('rate_usd') ? 'is-invalid' : '' }}" wire:model="rate_usd">
            @error('rate_usd')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-12 d-flex justify-content-end">
            <button type="button" class="btn btn-primary px-5" wire:click='submit' wire:loading.attr="disabled">
                <div wire:loading.delay wire:target="submit">
                    <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                Submit
            </button>
        </div>

    </div>
</div>