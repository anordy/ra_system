<div class="card-header">
    <h5 class="text-uppercase">Provisional Daily Receipts</h5>
    <div class="card-tools">
            <button class="btn btn-info btn-sm" wire:click="downloadPdf">
                <i class="bi bi-file-pdf"></i> Download Pdf
            </button>
    </div>
</div>
<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-condensed table-borderless">
                <thead class="border-bottom border-dark">
                    <tr>
                        <td colspan="8" class="text-center">{{ now()->firstOfMonth()->format('d/m/Y') }} to {{ now()->format('d/m/Y') }}</th>
                    </tr>
                    <tr>
                        <th colspan="8" class="text-center">Provisional Daily Receipts</th>
                    </tr>
                    <tr class="bg-secondary">
                        <th colspan="4" class="text-center">Today's Collections</th>
                        <th colspan="4" class="text-center">Collection to Date</th>
                    </tr>
                    <tr>
                        <th>Source</th>
                        <th>Shilings</th>
                        <th>Dollars</th>
                        <th></th>
                        <th></th>
                        <th>Shilings</th>
                        <th>Dollars</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="border-bottom border-dark">
                    {{-- <tr>
                        <th>Unguja</th>
                    </tr> --}}
                    @foreach ($taxTypes as $row)
                        <tr>
                            <td>{{ $row->name }}</td>
                            <td>{{ number_format($row->tzsDailyPayments,2) }}</td>
                            <td>{{ number_format($row->usdDailyPayments,2) }}</td>
                            <td></td>
                            <td></td>
                            <td>{{ number_format($row->tzsMonthlyPayments,2) }}</td>
                            <td>{{ number_format($row->usdMonthlyPayments,2) }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>{{ number_format($todayTzsTotalCollection,2) }}</th>
                        <th>{{ number_format($todayUsdTotalCollection,2) }}</th>
                        <th></th>
                        <th></th>
                        <th>{{ number_format($monthTzsTotalCollection,2) }}</th>
                        <th>{{ number_format($monthUsdTotalCollection,2) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>