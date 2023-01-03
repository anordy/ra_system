<div>
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="tax_type_id" class="d-flex justify-content-between'">
                <span>
                    Tax Type
                </span>
            </label>
            <select name="tax_type_id" id="tax_type_id" wire:model="tax_type_id"
                class="form-control {{ $errors->has('tax_type_id') ? 'is-invalid' : '' }}">
                <option value="all">All</option>
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

        @if ($tax_type_code == 'vat')
            <div class="col-md-4 form-group">
                <label for="tax_type_id" class="d-flex justify-content-between">
                    <span>
                        VAT Type
                    </span>
                </label>
                <select name="vat_type" id="vat_type" wire:model="vat_type"
                    class="form-control {{ $errors->has('vat_type') ? 'is-invalid' : '' }}">
                    <option value="">Select VAT Type</option>
                    @foreach ($optionVatTypes as $vatType)
                        <option value={{ $vatType }}>{{ $vatType }}</option>
                    @endforeach
                </select>
                @error('vat_type')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        @endif

        <div class="col-md-4 form-group">
            <label for="type" class="d-flex justify-content-between'">
                <span>
                    Report Type
                </span>
            </label>
            <select name="type" id="type" wire:model="type"
                class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}">
                <option value="">Select Report Type</option>
                @if ($tax_type_id)
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

        @if ($type == 'Filing')
        <div class="col-md-4 form-group">
            <label for="type" class="d-flex justify-content-between'">
                <span>
                    Filing Type
                </span>
            </label>
            <select wire:model="filing_report_type"
                class="form-control {{ $errors->has('filing_report_type') ? 'is-invalid' : '' }}">
                <option value="">Select Filing Type</option>
                @if ($type)
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

        @if ($type == 'Payment')
        <div class="col-md-4 form-group">
            <label for="type" class="d-flex justify-content-between'">
                <span>
                    Payment Type
                </span>
            </label>
            <select wire:model="payment_report_type"
                class="form-control {{ $errors->has('payment_report_type') ? 'is-invalid' : '' }}">
                <option value="">Select Payment Type</option>
                @if ($type)
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
                @if ($tax_type_id && $reportType)
                <option value="all">All</option>
                <option value="range">Custom Range</option>
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

        @if ($year == 'range')
        <div class="col-md-4 form-group">
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
        <div class="col-md-4 form-group">
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

        @if ($year != 'all' && $year != 'range')
        <div class="col-md-4 form-group">
            <label for="period" class="d-flex justify-content-between'">
                <span>
                    Period
                </span>
            </label>
            <select wire:model="period" id="period"
                class="form-control {{ $errors->has('period') ? 'is-invalid' : '' }}">
                <option value="">Select Period</option>
                @if ($tax_type_id && $reportType && $year)
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

        @if ($period == 'Semi-Annual')
        <div class="col-md-4 form-group">
            <label for="Quarter" class="d-flex justify-content-between'">
                <span>
                    Semi-Annual
                </span>
            </label>
            <select name="semiAnnual" id="Quarter"
                class="form-control {{ $errors->has('semiAnnual') ? 'is-invalid' : '' }}" wire:model="semiAnnual">
                <option value="">Select Semi-Annual term</option>
                @if ($year && $period && $tax_type_id && $reportType)
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

        @if ($period && $period == 'Quarterly')
        <div class="col-md-4 form-group">
            <label for="Quarter" class="d-flex justify-content-between'">
                <span>
                    Quarter
                </span>
            </label>
            <select name="quater" id="Quarter" wire:model="quater"
                class="form-control {{ $errors->has('quater') ? 'is-invalid' : '' }}">
                <option value="">Select Quater</option>
                @if ($year && $period && $tax_type_id && $reportType)
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

        @if ($period && $period == 'Monthly')
        <div class="col-md-4 form-group">
            <label for="Month" class="d-flex justify-content-between'">
                <span>
                    Months
                </span>
            </label>
            <select name="month" id="Month" wire:model="month"
                class="form-control {{ $errors->has('month') ? 'is-invalid' : '' }}">
                <option value="">Select Month</option>
                @if ($year && $period && $tax_type_id && $reportType)
                @foreach ($optionMonths as $key => $optionMonth)
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

    <!-- Show more filters -->
    <div>
        <div class="row">
            <div class="col-md-4 form-group">
                <button class="btn btn-primary btn-xs ml-2 mt-4" wire:click="toggleFilters">

                    @if ($showMoreFilters)
                    <i class="bi bi-filter mr-3"></i>
                    Hide More filters
                    @else
                    <i class="bi bi-filter"></i>
                    Show more Filters
                    @endif
                </button>
            </div>
        </div>
    </div>


    @if ($showMoreFilters)
    <!-- tax region -->
    <div>
        <div class="row pt-4">
            <div class="col-12">
                <div class="card-header"><b>Tax Region</b></div>
            </div>
        </div>
        <div class="row">
            @foreach ($optionTaxRegions as $id => $taxRegion)
            <div class="col-sm-2 form-group">
                <label class="d-flex justify-content-between" for="tax-region-{{ $id }}">
                    <span>
                        {{ $taxRegion }}
                    </span>
                </label>
                <input type="checkbox" wire:model="selectedTaxReginIds.{{ $id }}" id="tax-region-{{ $id }}">
            </div>
            @endforeach
        </div>
    </div>

    <!-- Physical Location -->
    <div>
        <div class="row pt-2">
            <div class="col-12">
                <div class="card-header"><b>Physical Location</b></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label>Region</label>
                <select wire:model="region" class="form-control @error('region') is-invalid @enderror">
                    <option value='all'>ALL</option>
                    @foreach ($regions as $reg)
                    <option value="{{ $reg->id }}">{{ $reg->name }}</option>
                    @endforeach
                </select>
            </div>
            @if ($region != 'all')
            <div class="col-md-4 form-group">
                <label>District</label>
                <select wire:model="district" class="form-control @error('district') is-invalid @enderror">
                    <option value='all'>ALL</option>
                    @foreach ($districts as $dist)
                    <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            @if ($district != 'all')
            <div class="col-md-4 form-group">
                <label><span>Ward</span></label>
                <select wire:model="ward" class="form-control @error('ward') is-invalid @enderror">
                    <option value='all'>ALL</option>
                    @foreach ($wards as $war)
                    <option value="{{ $war->id }}">{{ $war->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

        </div>
    </div>
    @endif

    <div class="row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div x-data>
                <button class="btn btn-warning ml-2" wire:click="preview">
                    <i class="bi bi-eye-fill"></i>
                    Preview Report
                </button>
            </div>
            @if ($hasData)
                <button class="btn btn-success ml-2" wire:click="exportExcel " wire:loading.attr="disabled">
                    <i class="bi bi-file-earmark-spreadsheet ml-1" wire:loading.remove wire:target="exportExcel"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                        wire:target="exportExcel"></i>
                    Export to Excel
                </button>

                <button class="btn btn-success ml-2" wire:click="exportPdf" wire:loading.attr="disabled">
                    <i class="fas fa-file-pdf ml-1" wire:loading.remove wire:target="exportPdf"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="exportPdf"></i>
                    Export to Pdf
                </button>
            @endif
        </div>
    </div>

    @if($hasData)
    <div class="mt-3">
        @livewire('reports.returns.report-preview-table',['parameters'=>$parameters])
    </div>
    @endif


</div>