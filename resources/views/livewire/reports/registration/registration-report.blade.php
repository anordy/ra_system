<div>
    <div class="row">
        <div class="col-md-4 form-group">
            <label class="d-flex justify-content-between'">
                <span>
                    Criteria
                </span>
            </label>
            <select wire:model="reportType"
                class="form-control {{ $errors->has('reportType') ? 'is-invalid' : '' }}">
                <option value="">Select Criteria</option>
                @foreach ($optionReportTypes as $key=>$report)
                <option value={{ $key }}>
                    {{ $report }}</option>
                @endforeach
            </select>
            @error('reportType')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        @if($reportType == 'Business-Reg-By-Nature')
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>
                        ISIC LEVEL I
                    </span>
                </label>
                <select wire:model="isic1Id" class="form-control {{ $errors->has('isic1Id') ? 'is-invalid' : '' }}">
                    <option value="">Select Level</option>
                    @foreach ($optionIsic1s as $isic1)
                    <option value={{ $isic1->id }}>
                        {{ $isic1->description }}</option>
                    @endforeach
                </select>
                @error('isic1Id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>
                        ISIC LEVEL II
                    </span>
                </label>
                <select wire:model="isic2Id" class="form-control {{ $errors->has('isic2Id') ? 'is-invalid' : '' }}">
                    <option value="">Select Level</option>
                    @foreach ($optionIsic2s as $isic2)
                    <option value={{ $isic2->id }}>
                        {{ $isic2->description }}</option>
                    @endforeach
                </select>
                @error('isic2Id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>
                        ISIC LEVEL III
                    </span>
                </label>
                <select wire:model="isic3Id" class="form-control {{ $errors->has('isic3Id') ? 'is-invalid' : '' }}">
                    <option value="">Select Level</option>
                    @foreach ($optionIsic3s as $isic3)
                    <option value={{ $isic3->id }}>
                        {{ $isic3->description }}</option>
                    @endforeach
                </select>
                @error('isic3Id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>
                        ISIC LEVEL IV
                    </span>
                </label>
                <select wire:model="isic4Id" class="form-control {{ $errors->has('isic4Id') ? 'is-invalid' : '' }}">
                    <option value="">Select Level</option>
                    @foreach ($optionIsic4s as $isic4)
                    <option value={{ $isic4->id }}>
                        {{ $isic4->description }}</option>
                    @endforeach
                </select>
                @error('isic4Id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        @endif

        @if($reportType == 'Business-Reg-By-TaxType')
            <div class="col-md-4 form-group">
                <label for="report_type" class="d-flex justify-content-between'">
                    <span>
                        Select Tax Type
                    </span>
                </label>
                <select wire:model="tax_type_id" class="form-control {{ $errors->has('tax_type_id') ? 'is-invalid' : '' }}">
                    <option value="">Select Tax Type</option>
                    @foreach ($optionTaxTypes as $tax)
                    <option value={{ $tax->id }}>
                        {{ $tax->name }}</option>
                    @endforeach
                </select>
                @error('tax_type_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        @endif

        @if($reportType == 'Business-Reg-By-Turn-Over')
        <div class="col-md-4 form-group">
            <label for="report_type" class="d-flex justify-content-between'">
                <span>
                    Turn Over Period
                </span>
            </label>
            <select wire:model="turn_over_type" class="form-control {{ $errors->has('turn_over_type') ? 'is-invalid' : '' }}">
                <option value="">Select Period</option>
                @foreach ($optionTurnOverTypes as $key=>$type)
                <option value={{ $key }}>
                    {{ $type }}</option>
                @endforeach
            </select>
            @error('turn_over_type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-4 form-group">
            <label for="report_type" class="d-flex justify-content-between'">
                <span>
                    From (Amount in TZS)
                </span>
            </label>
            <input wire:model="turn_over_from_amount" class="form-control {{ $errors->has('turn_over_from_amount') ? 'is-invalid' : '' }}">
            @error('turn_over_from_amount')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-4 form-group">
            <label for="report_type" class="d-flex justify-content-between'">
                <span>
                    To (Amount in TZS)
                </span>
            </label>
            <input wire:model="turn_over_to_amount" class="form-control {{ $errors->has('turn_over_to_amount') ? 'is-invalid' : '' }}">
            @error('turn_over_to_amount')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
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
            {{-- <button class="btn btn-success ml-2" wire:click="exportExcel " wire:loading.attr="disabled">
                <i class="bi bi-file-earmark-spreadsheet ml-1" wire:loading.remove wire:target="exportExcel"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                    wire:target="exportExcel"></i>
                Export to Excel
            </button>
            <button class="btn btn-success ml-2" wire:click="exportPdf" wire:loading.attr="disabled">
                <i class="bi bi-file-earmark-pdf ml-1" wire:loading.remove wire:target="exportPdf"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="exportPdf"></i>
                Export to Pdf
            </button> --}}
        </div>
    </div>


</div>