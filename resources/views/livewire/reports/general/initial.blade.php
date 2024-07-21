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
                <option value="">--choose report type---</option>
                @foreach ($report_types as $row)
                    @can($row->permission)
                        <option value={{ $row->id }}>{{ $row->name }}</option>
                    @endcan
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
                <option value="">--choose report---</option>
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

        @if (count($parameters ?? []) > 0)
            @foreach ($parameters as $i => $parameter)
                @if ($parameter['input_type'] === 'date')
                    <div class="col-md-4 form-group">
                        <label class="d-flex justify-content-between'">
                            <span>
                                {{ $parameter['name'] }}
                            </span>
                        </label>
                        <input type="date" wire:model="parameters.{{ $i }}.value" class="form-control">
                        @error('parameters.' . $i . '.code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                @elseif($parameter['input_type'] === 'select')
                    <div class="col-md-4 form-group">
                        <label class="d-flex justify-content-between'">
                            <span>
                                {{ $parameter['name'] }}
                            </span>
                        </label>

                        <select wire:model="parameters.{{ $i }}.value" class="form-control">
                            <option value="">--choose option---</option>
                            @if (str_starts_with($parameter['model_name'], 'SELECT'))
                                @foreach (json_decode(json_encode(\Illuminate\Support\Facades\DB::select($parameter['model_name'])), true) as $row)
                                    <option value="{{ $row['id'] }}">
                                        {{ $row[$parameter['display_name']] ?? 'N/A' }}
                                    </option>
                                @endforeach
                            @else
                                @foreach ($parameter['model_name']::get()->toArray() as $row)
                                    <option value="{{ $row['id'] }}">
                                        {{ $row[$parameter['display_name']] ?? 'N/A' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('parameters.' . $i . '.code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                @elseif($parameter['input_type'] === 'text')
                        <div class="col-md-4 form-group">
                            <label class="d-flex justify-content-between'">
                            <span>
                                {{ $parameter['name'] }}
                            </span>
                            </label>
                            <input type="text" wire:model="parameters.{{ $i }}.value" class="form-control">
                            @error('parameters.' . $i . '.code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                @elseif($parameter['input_type'] === 'dynamic')
                    <div class="col-md-4 form-group">
                        <label class="d-flex justify-content-between'">
                            <span>Duration</span>
                        </label>
                        <select class="form-control {{ $errors->has('duration') ? 'is-invalid' : '' }}"
                                wire:model="duration">
                            <option value="year">Yearly</option>
                            <option value="range">Custom Range</option>
                        </select>
                        @error('duration')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    @if($duration === \App\Enum\ReportStatus::Year)
                        <div class="col-md-4 form-group">
                            <label class="d-flex justify-content-between'">
                            <span>
                                Year
                            </span>
                            </label>
                            <select name="year" class="form-control {{ $errors->has('year') ? 'is-invalid' : '' }}"
                                    wire:model="year">
                                <option value="{{ \App\Enum\ReportStatus::all  }}">All</option>
                                @foreach ($years as $optionYear)
                                    <option value="{{ $optionYear }}">{{ $optionYear }}</option>
                                @endforeach
                            </select>
                            @error('year')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        @if($year != \App\Enum\ReportStatus::all)
                            <div class="col-md-4 form-group">
                                <label for="start_month" class="d-flex justify-content-between'">
                            <span>
                                Month
                            </span>
                                </label>
                                <select  class="form-control {{ $errors->has('month') ? 'is-invalid' : '' }}"
                                         wire:model="month">
                                    <option value="{{ \App\Enum\ReportStatus::all  }}">All</option>
                                    @foreach ($months as $key => $month)
                                        <option value="{{ $key }}">{{ $month }}</option>
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

                @endif

                @if($duration === \App\Enum\ReportStatus::range)
                    <div class="col-md-4 form-group">
                        <label class="d-flex justify-content-between">
                            <span>
                                Start Date
                            </span>
                        </label>
                        <input type="date" wire:model.defer="start_date" class="form-control">
                        @error('start_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="d-flex justify-content-between'">
                            <span>
                                End Date
                            </span>
                        </label>
                        <input type="date" wire:model.defer="end_date" class="form-control">
                        @error('end_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                @endif

            @endforeach
        @endif

        <div class="col-md-4 form-group">
            <label for="format" class="d-flex justify-content-between'">
                <span>
                    Format
                </span>
            </label>
            <select name="format" id="format" wire:model="format"
                    class="form-control {{ $errors->has('format') ? 'is-invalid' : '' }}">
                <option value="" selected>--Choose option--</option>
                @foreach (\App\Enum\ReportFormats::getConstants() as $reports)
                    <option value="{{ $reports }}">{{ $reports }}</option>
                @endforeach

            </select>
            @error('format')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4">
            <button class="btn btn-primary btn-cm" wire:click="submit">Submit</button>
        </div>

        @if ($fileName)
            <div class="col-md-12 d-flex justify-content-end mt-4">
                <a href="{{ route('reports.tax-payer.download.pdf', [$fileName]) }}" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf ml-1" wire:loading.remove wire:target="exportExcel"></i>
                    Download Pdf
                </a>
            </div>
        @endif
    </div>
</div>
