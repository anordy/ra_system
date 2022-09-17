<div>
    <div class="card pt-2">
        <div class="card-header text-uppercase font-weight-bold bg-grey ">
            Report Period
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="date_type" class="d-flex justify-content-between'">
                        <span>
                            Date Type
                        </span>
                    </label>
                    <select name="date_type" id="date_type" wire:model="date_type"
                        class="form-control {{ $errors->has($date_type) ? 'is-invalid' : '' }}">
                        <option value="created_at" selected>
                            Created At Date
                        </option>
                        <option value="paid_at">
                            Paid At Date
                        </option> 
                        <option value="payment_month">
                            Payment Month
                        </option> 
                        <option value="payment_year">
                            Payment Year
                        </option>          
                    </select>
                    @error('date_type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-3 form-group">
                    <label for="year" class="d-flex justify-content-between'">
                        <span>
                            Year
                        </span>
                    </label>
                    <select name="year" id="year" wire:model="year"
                        class="form-control {{ $errors->has($year) ? 'is-invalid' : '' }}">
                        @foreach ($optionYears as $optionYear)
                            <option value="{{ $optionYear }}">
                                {{ $optionYear }}
                            </option>
                        @endforeach
                    </select>
                    @error('year')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                @if ($year == 'Custom Range')
                    <div class="col-md-3 form-group">
                        <label class="d-flex justify-content-between'">
                            <span>Start Date</span>
                        </label>
                        <input type="date" class="form-control" wire:model="range_start">
                        @error('range_start')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="d-flex justify-content-between'">
                            <span>End Date</span>
                        </label>
                        <input type="date" class="form-control" wire:model="range_end">
                        @error('range_end')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                @endif
        
                @if ($showOptions || $year != 'Custom Range')
                    <div class="col-md-3 form-group">
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
                        <div class="col-md-3 form-group">
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
                        <div class="col-md-3 form-group">
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
                        <div class="col-md-3 form-group">
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
                <div class="col-md-3 form-group">
                    <label for="status" class="d-flex justify-content-between'">
                        <span>
                            Payment Status
                        </span>
                    </label>
                    <select name="status" id="status" wire:model="status"
                        class="form-control {{ $errors->has($status) ? 'is-invalid' : '' }}">
                        <option value="" selected>
                            All
                        </option>
                        <option value="{{\App\Enum\LeaseStatus::IN_ADVANCE_PAYMENT}}">
                            In Advance Payment
                        </option>
                        <option value="{{\App\Enum\LeaseStatus::ON_TIME_PAYMENT}}">
                            On Time Payment
                        </option>
                        <option value="{{\App\Enum\LeaseStatus::LATE_PAYMENT}}">
                            Late Payment
                        </option>
                        <option value="{{\App\Enum\LeaseStatus::PENDING}}">
                            Pending
                        </option>
                        <option value="{{\App\Enum\LeaseStatus::DEBT}}">
                            {{\App\Enum\LeaseStatus::DEBT}}
                        </option>
                        <option value="{{\App\Enum\LeaseStatus::CN_GENERATED}}">
                            Control number generated
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <div x-data>
                        <button class="btn btn-warning ml-2" wire:click="preview" x-on:mouseenter="$wire.preview()">
                            <i class="bi bi-eye-fill"></i>
                            Preview Report  
                        </button>
                    </div>
                    <button class="btn btn-success ml-2" wire:click="export " wire:loading.attr="disabled">
                        <i class="bi bi-file-earmark-spreadsheet ml-1" wire:loading.remove wire:target="export"></i>
                        <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                            wire:target="export"></i>
                        Export to Excel 
                    </button>
                    <button  class="btn btn-success ml-2"  wire:click="exportPdf" wire:loading.attr="disabled">
                        <i class="bi bi-file-earmark-spreadsheet ml-1" wire:loading.remove wire:target="exportPdf"></i>
                        <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                            wire:target="exportPdf"></i>
                        Export to Pdf  
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
            @livewire('land-lease.lease-payment-report-table')
        </div>
    </div>


</div>
