<div>
     <!-- report type -->
     <div class="card-header font-weight-bold bg-white text-uppercase">
        <b> Report Type</b>
        <div class="card-tools">
            <button class="btn btn-success mr-2"  wire:loading.attr="disabled">
                <i class="bi bi-file-earmark-excel mr-2" ></i>
                Export Excel
            </button>

           <button class="btn btn-primary mr-2"  wire:loading.attr="disabled">
               <i class="bi bi-file-pdf mr-2"  ></i>
               Download Pdf
           </button>
        </div>
        <div class="card-body px-1">
        <div class="row mt-4">
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>
                        Criteria
                    </span>
                </label>
                <select wire:model="reportType"
                    class="form-control {{ $errors->has('reportType') ? 'is-invalid' : '' }}">
                    <option value="all">All</option>
                    {{-- @foreach ($optionReportTypes as $key => $report)
                    <option value={{ $key }}>
                        {{ $report }}</option>
                    @endforeach --}}
                </select>
                @error('reportType')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>
                        Year
                    </span>
                </label>
                <select wire:model="year" class="form-control {{ $errors->has('year') ? 'is-invalid' : '' }}">
                    <option value="all">All</option>
                    {{-- <option value="range">Custome Range</option>
                    @foreach ($optionYears as $key => $y)
                    <option value={{ $y }}>
                        {{ $y }}</option>
                    @endforeach --}}
                </select>
                @error('year')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            @if ($year != 'all' && $year != 'range')
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>
                        Month
                    </span>
                </label>
                <select wire:model="month" class="form-control {{ $errors->has('month') ? 'is-invalid' : '' }}">
                    <option value="all">All</option>
                    {{-- @foreach ($optionMonths as $key => $m)
                    <option value={{ $key }}>
                        {{ $m }}</option>
                    @endforeach --}}
                </select>
                @error('month')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            @endif

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
             <!-- Show more filters -->
             {{-- <div class="col-md-4 form-group">
                <button class="btn btn-outline-primary btn-xs ml-2 mt-4" wire:click="toggleFilters">

                    @if ($showMoreFilters)
                    <i class="bi bi-filter mr-3"></i>
                    Hide More filters
                    @else
                    <i class="bi bi-filter"></i>
                    More Filters...
                    @endif
                </button>
            </div> --}}
     </div>

     @if ($showMoreFilters)
    <!-- tax region -->
    <div>
        <div class="row pt-4">
            <div class="col-12">
                <div class="card-header"><b>Revenue Type</b></div>
            </div>
        </div>
        <div class="row">
            @foreach ($leakages as $id => $row)
            <div class="col-sm-2 form-group">
                <label class="d-flex justify-content-between" for="leakage">
                    <span>
                        {{ $row }}
                    </span>
                </label>
                <input type="checkbox" disabled wire:model="leakage" id="leakage">
            </div>
            @endforeach
        </div>
    </div>

    @endif

    <div class="row mt-3 mx-2">
        <div class="col-md-12">
            <table class="table table-bordered">
                <tbody>
                {{-- <tr>
                    <th colspan="6" class="text-center bg-secondary">
                        @if ($range_start == $today)
                            <span class="text-success">Today's Collections ({{ date('d-M-Y',strtotime($today))}})</span>
                        @else
                            Collections From
                            <span class="text-primary"> {{ date('d-M-Y',strtotime($range_start)) }} </span>
                            to
                            <span class="text-primary"> {{ date('d-M-Y',strtotime($range_end)) }} </span>
                        @endif
                    </th>
                </tr> --}}
                <tr class="text-center">
                    <th colspan="1" class="table-active">Business Line</th>
                    <th colspan="1" class="table-active">Identified Amount</th>
                    <th colspan="1" class="table-active">Recovered Amount</th>
                    <th colspan="1" class="table-active">Under Recovery Amount</th>
                    <th colspan="1" class="table-active">Prevented Amount</th>
                    {{-- <th colspan="1" class="table-active">Currency</th> --}}
                </tr>
                @foreach ($reports as $row)
                 <tr >
                    <td class="text-left" style="font-weight: 500">{{ $row->channel}} ({{$row->currency}})</td>
                    <td class="text-right" style="font-weight: 500">{{ number_format($row->detected,2) }}</td>
                    <td class="text-right" style="font-weight: 500">{{ number_format($row->recovered,2) }}</td>
                    <td class="text-right" style="font-weight: 500">{{ number_format(($row->detected - $row->recovered),2)}}</td>
                    <td class="text-right" style="font-weight: 500"> {{ number_format($row->prevented,2)}}</td>
                 </tr>
                 @endforeach
                </tbody>
                <tfoot class="text-right">
                    @php
                    $totals = [];
                    foreach ($reports as $row) {
                        $currency = $row->currency;
                        if (!isset($totals[$currency])) {
                            $totals[$currency] = [
                                'detected' => 0,
                                'recovered' => 0,
                                'prevented' => 0,
                            ];
                        }
                        $totals[$currency]['detected'] += $row->detected;
                        $totals[$currency]['recovered'] += $row->recovered;
                        $totals[$currency]['prevented'] += $row->prevented;
                    }
                @endphp
        
                @foreach ($totals as $currency => $total)
                <tr>
                    <th class="text-left table-active" >Total ({{ $currency }})</th>
                    <td class="text-right table-active">{{ number_format($total['detected'], 2) }}</td>
                    <td class="text-right table-active">{{ number_format($total['recovered'], 2) }}</td>
                    <td class="text-right table-active">{{ number_format($total['detected'] - $total['recovered'], 2) }}</td>
                    <td class="text-right table-active">{{ number_format($total['prevented'], 2) }}</td>
                    {{-- <td class="text-left">{{ $currency }}</td> --}}
                </tr>
                @endforeach
                </tfoot>
            </table>
        </div>
    </div>

        </div>  
</div>




