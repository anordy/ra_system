<div>
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="tax_type_id" class="d-flex justify-content-between'">
                <span>
                    Tax Type
                </span>
            </label>
            <select name="tax_type_id" id="tax_type_id" wire:model="tax_type_id" class="form-control {{ $errors->has('tax_type_id') ? 'is-invalid' : '' }}">
                <option value="">Select Tax Type</option>
                @foreach ($optionTaxTypes as $taxType)
                <option value={{ $taxType->id }}>
                    {{ $taxType->name }}</option>
                @endforeach
            </select>
            @error('tax_type_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-4 form-group">
            <label for="type" class="d-flex justify-content-between'">
                <span>
                    Report Type
                </span>
            </label>
            <select name="type" id="type" wire:model="type" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}">
                <option value="">Select Report Type</option>
                @if($tax_type_id)
                @foreach ($optionReportTypes as $reportType)
                <option value={{ $reportType }}>
                    {{ $reportType }}</option>
                @endforeach
                @endif
            </select>
            @error('type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        @if($type=="filing")
        <div class="col-md-4 form-group">
            <label for="type" class="d-flex justify-content-between'">
                <span>
                    Filing Type
                </span>
            </label>
            <select wire:model="filing_report_type" class="form-control {{ $errors->has('filing_report_type') ? 'is-invalid' : '' }}">
                <option value="">Select Filing Type</option>
                @if($type)
                @foreach ($optionFilingTypes as $filing)
                <option value={{ $filing }}>
                    {{ $filing }}</option>
                @endforeach
                @endif
            </select>
            @error('filing_report_type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        @endif

        @if($type=="payment")
        <div class="col-md-4 form-group">
            <label for="type" class="d-flex justify-content-between'">
                <span>
                    Payment Type
                </span>
            </label>
            <select wire:model="payment_report_type" class="form-control {{ $errors->has('payment_report_type') ? 'is-invalid' : '' }}">
                <option value="">Select Filing Type</option>
                @if($type)
                @foreach ($optionPaymentTypes as $payment)
                <option value={{ $payment }}>
                    {{ $payment }}</option>
                @endforeach
                @endif
            </select>
            @error('payment_report_type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        @endif

        <div class="col-md-4 form-group">
            <label for="start_month" class="d-flex justify-content-between'">
                <span>
                    Year
                </span>
            </label>
            <select name="year" class="form-control {{ $errors->has('year') ? 'is-invalid' : '' }}" wire:model="year">
                <option value="">Select Year</option>
                @if($tax_type_id && $reportType)
                <option value="all">All</option>
                @foreach ($optionYears as $optionYear)
                <option value="{{ $optionYear }}">{{ $optionYear }}</option>
                @endforeach
                @endif
            </select>
            @error('year')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>



        @if ($year != 'all')
        <div class="col-md-4 form-group">
            <label for="period" class="d-flex justify-content-between'">
                <span>
                    Period
                </span>
            </label>
            <select wire:model="period" id="period" class="form-control {{ $errors->has('period') ? 'is-invalid' : '' }}">
                <option value="">Select Period</option>
                @if($tax_type_id && $reportType && $year)
                    @foreach ($optionPeriods as $optionPeriod)
                    <option value="{{ $optionPeriod }}">
                        {{ $optionPeriod }}</option>
                    @endforeach
                @endif
            </select>
            @error('period')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>


        @if($period =='Semi-Annual')
        <div class="col-md-4 form-group">
            <label for="Quarter" class="d-flex justify-content-between'">
                <span>
                    Semi-Annual
                </span>
            </label>
            <select name="semiAnnual" id="Quarter" class="form-control {{ $errors->has('semiAnnual') ? 'is-invalid' : '' }}" wire:model="semiAnnual">
                <option value="">Select Semi-Annual term</option>
                @if($year && $period && $tax_type_id && $reportType )
                @foreach ($optionSemiAnnuals as $optionSemiAnnual)
                <option value={{ $optionSemiAnnual }}>
                    {{ $optionSemiAnnual }}</option>
                @endforeach
                @endif
            </select>
            @error('semiAnnual')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        @endif

        @if($period && $period =='Quarterly')
        <div class="col-md-4 form-group">
            <label for="Quarter" class="d-flex justify-content-between'">
                <span>
                    Quarter
                </span>
            </label>
            <select name="quater" id="Quarter" wire:model="quater" class="form-control {{ $errors->has('quater') ? 'is-invalid' : '' }}">
                <option value="">Select Quater</option>
                @if($year && $period && $tax_type_id && $reportType )
                @foreach ($optionQuarters as $optionQuarter)
                <option value={{ $optionQuarter }}>
                    {{ $optionQuarter }}</option>
                @endforeach
                @endif
            </select>
            @error('quater')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        @endif


        @if($period && $period =='Monthly')
        <div class="col-md-4 form-group">
            <label for="Month" class="d-flex justify-content-between'">
                <span>
                    Months
                </span>
            </label>
            <select name="month" id="Month" wire:model="month" class="form-control {{ $errors->has('month') ? 'is-invalid' : '' }}">
                <option value="" >Select Month</option>
                @if($year && $period && $tax_type_id && $reportType )
                @foreach ($optionMonths as $key=> $optionMonth)
                <option value={{ $key }}>
                    {{ $optionMonth }}</option>
                @endforeach
                @endif
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
            <div x-data>
                <button class="btn btn-warning ml-2" wire:click="preview">
                    <i class="bi bi-eye-fill"></i>
                    Preview Report  
                </button>
            </div>
            <button  class="btn btn-success ml-2"  wire:click="export " wire:loading.attr="disabled">
                <i class="bi bi-file-earmark-spreadsheet ml-1" wire:loading.remove wire:target="export"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                    wire:target="export"></i>
                Generate Report  
            </button>
        </div>
    </div>


</div>