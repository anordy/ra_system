<div>
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="tax_type_id" class="d-flex justify-content-between'">
                <span>
                    Assessment Type
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
        <div class="col-md-4 form-group">
            <label for="start_month" class="d-flex justify-content-between'">
                <span>
                    Year
                </span>
            </label>
            <select name="year" class="form-control {{ $errors->has('year') ? 'is-invalid' : '' }}"
                wire:model="year">
                <option value="">Select Year</option>
                <option value="all">All</option>
                @foreach ($optionYears as $optionYear)
                    <option value="{{ $optionYear }}">{{ $optionYear }}</option>
                @endforeach
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
                <select wire:model="period" id="period"
                    class="form-control {{ $errors->has('period') ? 'is-invalid' : '' }}">
                    <option value="">Select Period</option>
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


            @if ($period == 'Semi-Annual')
                <div class="col-md-4 form-group">
                    <label for="Quarter" class="d-flex justify-content-between'">
                        <span>
                            Semi-Annual
                        </span>
                    </label>
                    <select name="semiAnnual" id="Quarter"
                        class="form-control {{ $errors->has('semiAnnual') ? 'is-invalid' : '' }}"
                        wire:model="semiAnnual">
                        <option value="">Select Semi-Annual term</option>
                        @if ($year && $period)
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
                        @if ($year && $period)
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
                        @if ($year && $period)
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

    <div class="row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div x-data>
                <button class="btn btn-warning ml-2" wire:click="preview">
                    <i class="bi bi-eye-fill"></i>
                    Preview Report
                </button>
            </div>
            <button class="btn btn-success ml-2" wire:click="exportExcel " wire:loading.attr="disabled">
                <i class="bi bi-file-earmark-spreadsheet ml-1" wire:loading.remove wire:target="exportExcel"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                    wire:target="exportExcel"></i>
                Export to Excel
            </button>

            <button class="btn btn-success ml-2" wire:click="exportPdf" wire:loading.attr="disabled">
                <i class="fas fa-file-pdf ml-1" wire:loading.remove wire:target="exportPdf"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                    wire:target="exportPdf"></i>
                Export to Pdf
            </button>
        </div>
    </div>
</div>
