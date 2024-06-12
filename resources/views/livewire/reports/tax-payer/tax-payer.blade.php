<div>
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="tax_type_id" class="d-flex justify-content-between'">
                <span>
                  Report Type
                </span>
            </label>
            <select name="report_type_id" id="report_type_id" wire:model="report_type_id"
                    class="form-control {{ $errors->has('report_type_id') ? 'is-invalid' : '' }}">
                <option value="" >--choose report type---</option>

            @foreach ($report_types as $row)
                    <option value={{ $row->id }}>{{ $row->name }}</option>
                @endforeach
            </select>
            @error('report_type_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-4 form-group">
            <label for="tax_type_id" class="d-flex justify-content-between'">
                <span>
                  Report
                </span>
            </label>
            <select name="report_code" id="report_code" wire:model="report_code"
                    class="form-control {{ $errors->has('report_code') ? 'is-invalid' : '' }}">
                <option value="" >--choose report---</option>
                @foreach ($reports as $row)

                    <option value={{ $row->code }}>
                        {{ $row->name }}</option>
                @endforeach
            </select>
            @error('report_code')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>


        <div class="col-md-4 form-group">
            <label for="start_date" class="d-flex justify-content-between'">
                <span>
                  Start Date
                </span>
            </label>
            <input name="start_date" id="start_date" type="date" wire:model="start_date"
                   class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}">

            @error('start_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-4 form-group">
            <label for="end_date" class="d-flex justify-content-between'">
                <span>
                  End Date
                </span>
            </label>
            <input name="end_date" id="end_date" type="date" wire:model="end_date"
                   class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}">
            @error('end_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-4 form-group">
            <label for="format" class="d-flex justify-content-between'">
                <span>
                  Format
                </span>
            </label>
            <select name="format" id="format" wire:model="format"
                    class="form-control {{ $errors->has('format') ? 'is-invalid' : '' }}">
                <option value="EXCEL" >EXCEL</option>
                <option value="P" >PDF</option>

            </select>
            @error('format')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4 ">
            <button class="btn btn-primary btn-cm" wire:click="submit">Submit</button>
        </div>
    </div>
</div>