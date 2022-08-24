<div>
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="tax_type_id" class="d-flex justify-content-between'">
                <span>Report Type</span>
            </label>
            <select name="report_type_id" id="report_type_id" wire:model="report_type_id"
                class="form-control {{ $errors->has('report_type_id') ? 'is-invalid' : '' }}">
                <option value="">Select Report Type</option>
                @foreach ($optionReportTypes as $key=>$reportType)
                <option value={{ $key }}>{{ $reportType }}</option>
                @endforeach
            </select>
            @error('report_type_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <p>{{ $report_type_id }}</p>
        </div>

        <div class="col-md-4 form-group">
            <label for="" class="d-flex justify-content-between">
                <span>Filter By</span>
            </label>
            <select name="" id="report_type_id" wire:model=""
                class="form-control {{ $errors->has('report_type_id') ? 'is-invalid' : '' }}">
                <option value="">Select Report Type</option>
                @foreach ($optionReportTypes as $key=>$reportType)
                <option value={{ $key }}>{{ $reportType }}</option>
                @endforeach
            </select>
            @error('report_type_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <p>{{ $report_type_id }}</p>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div x-data>
                <button class="btn btn-warning ml-2" wire:click="preview" wire:loading.attr="disabled">
                    <i class="bi bi-eye-fill" wire:loading.remove wire:target="preview"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                        wire:target="preview"></i>
                    Preview Report
                </button>
            </div>
            <button class="btn btn-success ml-2" wire:click="exportExcel" wire:loading.attr="disabled">
                <i class="bi bi-file-earmark-spreadsheet ml-1" wire:loading.remove wire:target="exportExcel"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                    wire:target="exportExcel"></i>
                Export to Pdf
            </button>
            <button class="btn btn-success ml-2" wire:click="exportPdf" wire:loading.attr="disabled">
                <i class="bi bi-file-earmark-spreadsheet ml-1" wire:loading.remove wire:target="exportPdf"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="exportPdf"></i>
                Export to Pdf
            </button>
        </div>
    </div>
</div>