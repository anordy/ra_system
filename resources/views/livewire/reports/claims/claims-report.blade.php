<div>
    <div class="row">

        <div class="col-md-4 form-group">
            <label for="taxpayer" class="d-flex justify-content-between'">
                <span>
                   Taxpayers
                </span>
            </label>
            <select name="taxpayer" id="taxpayer" wire:model="taxpayer"
                    class="form-control {{ $errors->has('taxpayer') ? 'is-invalid' : '' }}">
                <option value="all">All Taxpayers</option>
                @foreach($optionTaxPayers as $optionTaxPayer)
                <option value="{{$optionTaxPayer->id}}">{{$optionTaxPayer->first_name}} {{$optionTaxPayer->middle_name}} {{$optionTaxPayer->last_name}}</option>
                @endforeach
            </select>
            @error('taxpayer')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-4 form-group">
            <label for="status" class="d-flex justify-content-between'">
                <span>
                   Claim Status
                </span>
            </label>
            <select name="status" id="status" wire:model="status"
                    class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
                <option value="">select claim status</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="pending">Pending</option>
                <option value="both">Both</option>
            </select>
            @error('status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        @if($status == 'approved' || $status == 'both')
        <div class="col-md-4 form-group">
            <label for="payment_status" class="d-flex justify-content-between'">
                <span>
                    Payment Status
                </span>
            </label>
            <select name="payment_status" id="payment_status" wire:model="payment_status"
                    class="form-control {{ $errors->has('payment_status') ? 'is-invalid' : '' }}">
                <option value="">select payment status</option>
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
                <option value="partially-paid">Partially Paid</option>
                <option value="all">Both</option>

            </select>
            @error('payment_status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        @endif

        <div class="col-md-4 form-group">
            <label for="duration" class="d-flex justify-content-between'">
                <span>
                    Duration Option
                </span>
            </label>
            <select name="duration" id="duration" wire:model="duration"
                    class="form-control {{ $errors->has('duration') ? 'is-invalid' : '' }}">
                <option value="">select duration option</option>
                <option value="yearly">Yearly</option>
                <option value="date_range">Date Range</option>

            </select>
            @error('duration')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        @if($duration == 'date_range')
            <div class="col-md-4 form-group">
                <label for="from" class="d-flex justify-content-between'">
                <span>
                    From
                </span>
                </label>
                <input max="{{$today}}" name="from" wire:model="from" type="date" class="form-control {{ $errors->has('from') ? 'is-invalid' : '' }}">
                @error('from')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="col-md-4 form-group">
                <label for="to" class="d-flex justify-content-between'">
                <span>
                    To
                </span>
                </label>
                <input max="{{$today}}" name="to" wire:model="to" type="date" class="form-control {{ $errors->has('to') ? 'is-invalid' : '' }}">
                @error('to')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        @endif

        @if($duration == 'yearly')
            <div class="col-md-4 form-group">
                <label for="year" class="d-flex justify-content-between'">
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
        @endif

        @if (!empty($year) && $year != 'all' && $duration == 'yearly')
            <div class="col-md-4 form-group">
                <label for="period" class="d-flex justify-content-between'">
                    <span>
                        Period
                    </span>
                </label>
                <select wire:model="period" id="period"
                        class="form-control {{ $errors->has('period') ? 'is-invalid' : '' }}">
                    <option value="">Select Period</option>
                    @if ($year)
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
            @can('managerial-claim-report-preview')
            <div x-data>
                <button class="btn btn-warning ml-2" wire:click="preview">
                    <i class="bi bi-eye-fill"></i>
                    Preview Report
                </button>
            </div>
            @endcan

            @can('managerial-claim-report-excel')
            <button class="btn btn-success ml-2" wire:click="exportExcel " wire:loading.attr="disabled">
                <i class="bi bi-file-earmark-spreadsheet ml-1" wire:loading.remove wire:target="exportExcel"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                   wire:target="exportExcel"></i>
                Export to Excel
            </button>
            @endcan

            @can('managerial-claim-report-pdf')
            <button class="btn btn-danger ml-2" wire:click="exportPdf" wire:loading.attr="disabled">
                <i class="fas fa-file-pdf ml-1" wire:loading.remove wire:target="exportPdf"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                   wire:target="exportPdf"></i>
                Export to Pdf
            </button>
            @endcan
        </div>
    </div>


</div>
