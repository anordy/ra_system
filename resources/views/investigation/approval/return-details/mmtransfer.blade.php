<div class="card">
    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Declared Sales Analysis</h6>
        <hr>
        <div class="row mx-2">
            @foreach ($returns as $year => $return)
            <strong class="px-2">{{ $year }}</strong>
                @php
                    $LMTRANSFER = 0;
                    $LWITHDRAWALS = 0;
                    $totalValue = 0;
                    $totalVat = 0;
                @endphp
                <table class="table table-sm table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>Month</th>
                            @foreach ($headersEmTransaction as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                            <th>Total</th>
                            <th>Output VAT</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($return as $item)
                            <tr>
                                <td>{{ $item['month'] }}</td>
                                <td>{{ $item['LMTRANSFER'] }}</td>
                                <td>{{ $item['LWITHDRAWALS'] }}</td>
                                <td>{{ $item['totalValue'] }}</td>
                                <td>{{ $item['totalVat'] }}</td>
                            </tr>
                            @php
                                $LMTRANSFER += $item['LMTRANSFER'];
                                $LWITHDRAWALS += $item['LWITHDRAWALS'];
                                $totalValue += $item['totalValue'];
                                $totalVat += $item['totalVat'];
                            @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold">
                            <td>TOTAL</td>
                            <td>{{ number_format($LMTRANSFER, 2) }}</td>
                            <td>{{ number_format($LWITHDRAWALS, 2) }}</td>
                            <td>{{ number_format($totalValue, 2) }}</td>
                            <td>{{ number_format($totalVat, 2) }}</td>
                        </tr>
                    </tfoot>

                </table>
            @endforeach

        </div>
    </div>
</div>