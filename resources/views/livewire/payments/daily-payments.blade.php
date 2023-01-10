<div>
    <div class="card-header">
        <h5 class="text-uppercase">Provisional Daily Receipts</h5>
        <div class="card-tools">
            <button class="btn btn-success mr-2" wire:click="downloadExcel" wire:loading.attr="disabled">
                <i class="bi bi-file-earmark-excel mr-2" wire:loading.remove wire:target="downloadExcel"></i>
                <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                    wire:target="downloadExcel"></i> Download Excel
            </button>
            
            <button class="btn btn-primary mr-2" wire:click="downloadPdf" wire:loading.attr="disabled">
                <i class="bi bi-file-pdf mr-2" wire:loading.remove wire:target="downloadPdf"></i>
                <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                    wire:target="downloadPdf"></i> Download Pdf
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row mx-1">
            <div class="col-md-3  flex-grow-1 form-group">
                <label class="d-flex justify-content-between font-weight-bold">Start Date</label>
                <input type="date" max="{{ $today }}" class="form-control" wire:model.defer="range_start">
                @error('range_start')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3 flex-grow-1 form-group">
                <label class="d-flex justify-content-between font-weight-bold">End Date</label>
                <input type="date" min="{{ $range_start ?? $today }}" max="{{$today }}" class="form-control"
                    wire:model.defer="range_end">
                @error('range_end')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex align-items-end pb-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <button class="btn btn-primary mr-2" wire:click="search" wire:loading.attr="disabled">
                        <i class="fas fa-filter mr-2" wire:loading.remove wire:target="search"></i>
                        <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                            wire:target="search"></i> Search
                    </button>
                </div>
            </div>
        </div>

        <div class="row mt-3 mx-2">
            <div class="col-md-12">
                <table class="table table-condensed table-bordered">
                    <thead class="border-bottom border-dark">
                        <tr>
                            <th colspan="3" class="text-center bg-secondary">
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
                            <th>Shilings</th>
                            <th>Dollars</th>
                        </tr>
                    </thead>
                    <tbody class="border-bottom border-dark text-right">
                        @if ($taxTypes->isNotEmpty())
                            @foreach ($taxTypes as $row)
                            <tr>
                                <td class="text-left">{{ $row->name }}</td>
                                <td>{{ number_format($row->getTotalPaymentsPerCurrency('TZS',$range_start,$range_end),2) }}</td>
                                <td>{{ number_format($row->getTotalPaymentsPerCurrency('USD',$range_start,$range_end),2) }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center text-info">No Data available</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot class="text-right">
                        <tr>
                            <th class="text-left">Total</th>
                            <th>{{ number_format($vars['tzsTotalCollection'],2) }}</th>
                            <th>{{ number_format($vars['usdTotalCollection'],2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>