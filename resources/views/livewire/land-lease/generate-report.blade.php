<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    {{-- @include('layouts.component.messages') --}}
    <div class="card pt-2">
        <div class="card-header text-uppercase font-weight-bold bg-grey ">
            Report Period
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="start_month" class="d-flex justify-content-between'">
                        <span>
                            Year
                        </span>
                    </label>
                    <select name="year" id="start_month" wire:model="year"
                        class="form-control {{ $errors->has($year) ? 'is-invalid' : '' }}"
                        {{-- wire:changed="preview" --}}
                        >
                        @foreach ($optionYears as $optionYear)
                            <option value="{{ $optionYear }}">
                                {{ $optionYear }}</option>
                        @endforeach
                    </select>
                    @error('year')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
        
                @if ($showOptions)
                    <div class="col-md-4 form-group">
                        <label for="Period" class="d-flex justify-content-between'">
                            <span>
                                Period
                            </span>
                        </label>
                        <select name="period" id="Period" wire:model="period"
                            class="form-control {{ $errors->has($period) ? 'is-invalid' : '' }}">
                            <option value="" disabled>Select Period</option>
                            @foreach ($optionPeriods as $optionPeriod)
                                <option value="{{ $optionPeriod }}">
                                    {{ $optionPeriod }}</option>
                            @endforeach
                        </select>
                        @error('period')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    @if ($showSemiAnnuals)
                        <div class="col-md-4 form-group">
                            <label for="Quarter" class="d-flex justify-content-between'">
                                <span>
                                    Semi-Annual
                                </span>
                            </label>
                            <select name="semiAnnual" id="Quarter" wire:model="semiAnnual"
                                class="form-control {{ $errors->has($semiAnnual) ? 'is-invalid' : '' }}">
                                <option value="" disabled>Select Semi-Annual term</option>
                                @foreach ($optionSemiAnnuals as $optionSemiAnnual)
                                    <option value={{ $optionSemiAnnual }}>
                                        {{ $optionSemiAnnual }}</option>
                                @endforeach
                            </select>
                            @error('semiAnnual')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    @endif
                    @if ($showQuarters)
                        <div class="col-md-4 form-group">
                            <label for="Quarter" class="d-flex justify-content-between'">
                                <span>
                                    Quarter
                                </span>
                            </label>
                            <select name="quater" id="Quarter" wire:model="quater"
                                class="form-control {{ $errors->has($quater) ? 'is-invalid' : '' }}">
                                <option value="" disabled>Select Quater</option>
                                @foreach ($optionQuarters as $optionQuarter)
                                    <option value={{ $optionQuarter }}>
                                        {{ $optionQuarter }}</option>
                                @endforeach
                            </select>
                            @error('quater')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    @endif
                    @if ($showMonths)
                        <div class="col-md-4 form-group">
                            <label for="Month" class="d-flex justify-content-between'">
                                <span>
                                    Months
                                </span>
                            </label>
                            <select name="month" id="Month" wire:model="month"
                                class="form-control {{ $errors->has($month) ? 'is-invalid' : '' }}">
                                <option value="" disabled>Select Month</option>
                                @foreach ($optionMonths as $key => $optionMonth)
                                    <option value={{ $key }}>
                                        {{ $optionMonth }}</option>
                                @endforeach
                            </select>
                            @error('month')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    @endif
                @endif
            </div>

            <div class="row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    {{-- <button class="btn btn-warning ml-1" wire:click="preview" wire:loading.attr="disabled">
                        <i class="bi bi-eye-fill ml-1" wire:loading.remove wire:target="preview"></i>
                        <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                            wire:target="preview"></i>
                        Preview  
                    </button> --}}
                   

                    <div x-data>
                        {{-- <h1 x-text="$wire.count"></h1> --}}
                 
                        {{-- <button x-on:click="$wire.increment()">Increment</button> --}}
                        <button class="btn btn-warning ml-2" wire:click="preview" x-on:mouseenter="$wire.preview()">
                            <i class="bi bi-eye-fill"></i>
                            Preview Report  
                        </button>
                    </div>
                    <button class="btn btn-success ml-2" wire:click="export " wire:loading.attr="disabled">
                        <i class="bi bi-file-earmark-spreadsheet ml-1" wire:loading.remove wire:target="export"></i>
                        <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                            wire:target="export"></i>
                        Generate Report  
                    </button>
                </div>

               
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-grey ">
            Report Preview
        </div>
        <div class="card-body">
            @livewire('land-lease.land-lease-report-table')
        </div>
    </div>
    



</div>
