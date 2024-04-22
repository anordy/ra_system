<div>
    <div class="row">

        <div class="col-md-4 form-group">
            <label for="tax_type_id" class="d-flex justify-content-between'">
                <span>
                    Payment Categories
                </span>
            </label>
            <select name="payment_category" id="payment_category" wire:model="payment_category"
                    class="form-control {{ $errors->has('payment_category') ? 'is-invalid' : '' }}">
                <option value="">Choose Option</option>
                <option value="returns">Returns Payments</option>
                <option value="consultant">Tax Consultant Payments</option>
            </select>
            @error('payment_category')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        @if($payment_category == 'returns')
            <div class="col-md-4 form-group">
                <label for="tax_type_id" class="d-flex justify-content-between'">
                <span>
                    Tax Return Types
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
        @endif

        {{--        @if ($tax_type_code == 'vat')--}}
        {{--            <div class="col-md-4 form-group">--}}
        {{--                <label for="tax_type_id" class="d-flex justify-content-between">--}}
        {{--                    <span>--}}
        {{--                        VAT Type--}}
        {{--                    </span>--}}
        {{--                </label>--}}
        {{--                <select name="vat_type" id="vat_type" wire:model="vat_type"--}}
        {{--                        class="form-control {{ $errors->has('vat_type') ? 'is-invalid' : '' }}">--}}
        {{--                    <option value="">Select VAT Type</option>--}}
        {{--                    @foreach ($optionVatTypes as $vatType)--}}
        {{--                        <option value={{ $vatType }}>{{ $vatType }}</option>--}}
        {{--                    @endforeach--}}
        {{--                </select>--}}
        {{--                @error('vat_type')--}}
        {{--                <div class="invalid-feedback">--}}
        {{--                    {{ $message }}--}}
        {{--                </div>--}}
        {{--                @enderror--}}
        {{--            </div>--}}
        {{--        @endif--}}

        <div class="col-md-4 form-group">
            <label for="type" class="d-flex justify-content-between'">
                <span>
                    Status
                </span>
            </label>
            <select name="status" id="status" wire:model="status"
                    class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
                <option value="all">All</option>
                <option value="paid">Paid</option>
                <option value="pending">Pending</option>
                {{--                <option value="">Paid Partially</option>--}}
            </select>
            @error('status')
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
            <select name="year" class="form-control {{ $errors->has('year') ? 'is-invalid' : '' }}" wire:model="year">
                <option value="">Select Year</option>
                @if ($tax_type_id)
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
                    @if ($tax_type_id && $year)
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
                        @if ($year && $period && $tax_type_id)
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
                        <option value="">Select Quarter</option>
                        @if ($year && $period && $tax_type_id)
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
                        @if ($year && $period && $tax_type_id)
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

            <button class="btn btn-danger ml-2" wire:click="exportPdf" wire:loading.attr="disabled">
                <i class="fas fa-file-pdf ml-1" wire:loading.remove wire:target="exportPdf"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="exportPdf"></i>
                Export to Pdf
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mt-3">
            @if($isReturn)
                <table class="table table-bordered table-striped normal-text">
                    <thead>
                    <th>Business Name</th>
                    <th>Location Name</th>
                    <th>Filed By</th>
                    <th>Principal Amount</th>
                    <th>Interest</th>
                    <th>Penalty</th>
                    <th>Infrastructure</th>
                    <th>Total Amount</th>
                    <th>Outstanding Amount</th>
                    <th>Currency</th>
                    <th>Payment Status</th>
                    </thead>
                    <tbody>
                    @foreach ($previewData as $item)
                        <tr>
                            <td>{{ $item->business->name }}</td>
                            <td>{{ $item->location->name }}</td>
                            <td>{{ $item->taxpayer->first_name.' '. $item->taxpayer->middle_name.' '. $item->taxpayer->last_name }}</td>
                            <td>{{ $item->principal }}</td>
                            <td>{{ $item->interest }}</td>
                            <td>{{ $item->penalty }}</td>
                            <td>{{ $item->infrastructure }}</td>
                            <td>{{ $item->total_amount }}</td>
                            <td>{{ $item->outstanding_amount }}</td>
                            <td>{{ $item->currency }}</td>
                            <td>
                                @if($item->payment_status == \App\Enum\BillStatus::COMPLETE)
                                    Paid
                                @else
                                    Not Paid
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif

            @if($isConsultant)
                <table class="table table-bordered table-striped normal-text">
                    <thead>
                    <tr>
                        <th>
                            <strong>S/N</strong>
                        </th>
                        <th>
                            <strong>Full Name</strong>
                        </th>
                        <th>
                            <strong>Phone Number</strong>
                        </th>
                        <th>
                            <strong>Email</strong>
                        </th>
                        <th>
                            <strong>Amount</strong>
                        </th>
                        <th>
                            <strong>Currency</strong>
                        </th>
                        <th>
                            <strong>Description</strong>
                        </th>
                        <th>
                            <strong>Control Number</strong>
                        </th>
                        <th>
                            <strong>Status</strong>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($previewData as $index => $record)
                        <tr>
                            <td>
                                {{ $index + 1 }}
                            </td>
                            <td>
                                {{ $record->payer_name ?? '-' }}
                            </td>
                            <td>
                                {{ $record->payer_phone_number ?? '-' }}
                            </td>
                            <td>
                                {{ $record->payer_email ?? '-' }}
                            </td>

                            <td>
                                {{ $record->amount ?? '-' }}
                            </td>
                            <td>
                                {{ $record->currency ?? '-' }}
                            </td>

                            <td>
                                {{ $record->description ?? '-' }}
                            </td>

                            <td>
                                {{ $record->control_number ?? '-' }}
                            </td>
                            <td>
                                {{ $record->status ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>


</div>