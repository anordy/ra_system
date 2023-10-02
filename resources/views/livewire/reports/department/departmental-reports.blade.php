<div>
    <div class="card-header font-weight-bold bg-white text-uppercase">
        Managerial Departmental Reports
        <div class="card-tools">
            <button class="btn btn-success mr-2" wire:click="exportExcel" wire:loading.attr="disabled">
                <i class="bi bi-file-earmark-excel mr-2" wire:loading.remove wire:target="exportExcel"></i>
                <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                   wire:target="exportExcel"></i> Export Excel
            </button>

{{--            <button class="btn btn-primary mr-2" wire:click="downloadPdf" wire:loading.attr="disabled">--}}
{{--                <i class="bi bi-file-pdf mr-2" wire:loading.remove wire:target="downloadPdf"></i>--}}
{{--                <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading--}}
{{--                   wire:target="downloadPdf"></i> Download Pdf--}}
{{--            </button>--}}
        </div>
    </div>
    <div class="card-body px-1">
        <div class="row mx-1">
            <div class="col-md-3 form-group">
                <label for="location" class="font-weight-bold">Location</label>
                <select id="location" name="location" class="form-control" wire:model="location">
                    <option value="all">All</option>
                    <option value="unguja">Unguja</option>
                    <option value="pemba">Pemba</option>
                </select>
                @error('location')
                <div class="text-danger">
                    {{ $message }}
                </div>
                @enderror
            </div>

            @if($location == 'unguja')
                <div class="col-md-3 form-group">
                    <label for="department_type" class="font-weight-bold">Department Type</label>
                    <select name="department_type" class="form-control" wire:model="department_type">
                        <option value="all">All</option>
                        @foreach ($optionsReportTypes as $key=>$value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('department_type')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            @endif

            <div class="col-md-3  flex-grow-1 form-group">
                <label class="d-flex justify-content-between font-weight-bold">Start Date</label>
                <input type="date" max="{{ $today }}" class="form-control" wire:model="range_start">
                @error('range_start')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3 flex-grow-1 form-group">
                <label class="d-flex justify-content-between font-weight-bold">End Date</label>
                <input type="date" min="{{ $range_start ?? $today }}" max="{{$today }}" class="form-control"
                       wire:model="range_end">
                @error('range_end')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- tax region -->
            @if($department_type == 'domestic-taxes' || $location == 'pemba')
                <div class="col-md-12">
                    <label class="font-weight-bold">Tax Region</label>
                    <div class="row">
                        @foreach ($taxRegions as $id => $name)
                            <div class="col-sm-2 form-group">
                                <label for="tax-region-{{ $id }}">
                                    <input class="mr-2" type="checkbox" wire:model="selectedTaxReginIds.{{ $id }}" id="tax-region-{{ $id }}">
                                    {{ $name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

{{--            <div class="col-md-12 d-flex align-items-end pb-3">--}}
{{--                <div class="col-md-12 d-flex justify-content-end">--}}
{{--                    <button class="btn btn-primary mr-2" wire:click="search" wire:loading.attr="disabled">--}}
{{--                        <i class="fas fa-filter mr-2" wire:loading.remove wire:target="search"></i>--}}
{{--                        <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading--}}
{{--                            wire:target="search"></i> Search--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>

        <div class="row mt-3 mx-2">
            <div class="col-md-12">
                <table class="table table-condensed table-bordered">
                    <thead class="border-bottom border-dark">
                    <tr>
                        <th colspan="4" class="text-center bg-secondary">
                            @if ($range_start == $today)
                                <span class="text-success">Today's Collections ({{ date('d-M-Y',strtotime($today))}})</span>
                            @else
                                Collections From
                                <span class="text-primary"> {{ date('d-M-Y',strtotime($range_start)) }} </span>
                                to
                                <span class="text-primary"> {{ date('d-M-Y',strtotime($range_end)) }} </span>
                            @endif
                        </th>
                    </tr>
                    <tr class="text-center">
                        <th class="text-left">Source</th>
                        <th>TZS</th>
                        <th>USD</th>
                    </tr>
                    </thead>
                    <tbody class="border-bottom border-dark text-right">
                    @if ($nonRevenueTaxTypes->isNotEmpty() && !($department_type == 'domestic-taxes' && $location == \App\Models\Region::UNGUJA))
                        <tr class="text-center">
                            <th colspan="3" class="text-uppercase">Non Tax Revenue Department</th>
                        </tr>
                        @foreach ($nonRevenueTaxTypes as $row)
                            <tr>
                                <td class="text-left">{{ $row->name }}</td>
                                <td>{{ isset($report['TZS'][$row->id]) ? number_format($report['TZS'][$row->id],2) : '0' }}</td>
                                <td>{{ isset($report['USD'][$row->id]) ? number_format($report['USD'][$row->id],2) : '0' }}</td>
                            </tr>
                        @endforeach
                    @endif

                    @if ($domesticTaxTypes->isNotEmpty()  && !($department_type == 'non-tax-revenue' && $location == \App\Models\Region::UNGUJA))
                        <tr class="text-center">
                            <th colspan="3" class="text-uppercase">Domestic Taxes Department</th>
                        </tr>
                        @foreach ($domesticTaxTypes as $row)
                            <tr>
                                <td class="text-left">{{ $row->name }}</td>
                                <td>{{ isset($report['TZS'][$row->id]) ? number_format($report['TZS'][$row->id],2) : '0' }}</td>
                                <td>{{ isset($report['USD'][$row->id]) ? number_format($report['USD'][$row->id],2) : '0' }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                    <tfoot class="text-right">
                        <tr>
                            <th class="text-left">Total</th>
                            <th>{{ number_format(array_sum($report['TZS'] ?? [0]), 2) }}</th>
                            <th>{{ number_format(array_sum($report['USD'] ?? [0]), 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>