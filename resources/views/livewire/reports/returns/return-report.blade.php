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
                <option value="All">All</option>
                @foreach ($subVatOptions as $vatType)
                    <option value="{{ $vatType->id }}">{{ $vatType->name }}</option>
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
            <label class="d-flex justify-content-between'">
                <span>Start Date</span>
            </label>
            <input type="date" class="form-control" wire:model="range_start" max="{{ $range_end }}">
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
            <input type="date" class="form-control" wire:model="range_end" min="{{ $range_start }}" max="{{ $today }}">
            @error('range_end')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <!-- Show more filters -->

        <div class="col-md-4 form-group">
            <button class="btn btn-outline-info btn-xs ml-2 mt-4" wire:click="toggleFilters">

                @if ($showMoreFilters)
                <i class="bi bi-filter mr-3"></i>
                Hide More filters
                @else
                <i class="bi bi-filter"></i>
                More Filters..
                @endif
            </button>
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
            <button class="btn btn-primary ml-2" wire:click="preview" wire:loading.attr="disabled">
                <i class="bi bi-funnel ml-1" wire:loading.remove wire:target="preview"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="preview"></i>
                Search
            </button>
            @if ($hasData)
            <button class="btn btn-success ml-2" wire:click="exportExcel" wire:loading.attr="disabled">
                <i class="bi bi-file-earmark-spreadsheet ml-1" wire:loading.remove wire:target="exportExcel"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                    wire:target="exportExcel"></i>
                Export to Excel
            </button>

            <button class="btn btn-info ml-2" wire:click="exportPdf" wire:loading.attr="disabled">
                <i class="fas fa-file-pdf ml-1" wire:loading.remove wire:target="exportPdf"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="exportPdf"></i>
                Export to Pdf
            </button>
            @endif
        </div>
    </div>

    @if($hasData)
    <div class="mt-3">
        <livewire:reports.returns.report-preview-table :parameters="$parameters" key="{{ now() }}" />
    </div>
    @endif


</div>