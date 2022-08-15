<div class="card">
    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Declared Sales Analysis</h6>
        <hr>
        <div class="row mx-2">
            @foreach ($returns as $year => $return)
                <strong>{{ $year }}</strong>
                @php
                    $CWC = 0;
                    $EMTC = 0;
                    $MLPF = 0;
                    $MBTC = 0;
                    $SPCF = 0;
                    $ODFLC = 0;
                    $ComR = 0;
                    $RSF = 0;
                    $PVTS = 0;
                    $ASF = 0;
                    $FOTHER = 0;
                    $totalValue = 0;
                    $totalVat = 0;
                @endphp
                <table class="table table-sm table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>Month</th>
                            @foreach ($headersBfo as $header)
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
                                <td>{{ $item['CWC'] }}</td>
                                <td>{{ $item['EMTC'] }}</td>
                                <td>{{ $item['MLPF'] }}</td>
                                <td>{{ $item['MBTC'] }}</td>
                                <td>{{ $item['SPCF'] }}</td>
                                <td>{{ $item['ODFLC'] }}</td>
                                <td>{{ $item['ComR'] }}</td>
                                <td>{{ $item['RSF'] }}</td>
                                <td>{{ $item['PVTS'] }}</td>
                                <td>{{ $item['ASF'] }}</td>
                                <td>{{ $item['FOTHER'] }}</td>
                                <td>{{ $item['totalValue'] }}</td>
                                <td>{{ $item['totalVat'] }}</td>
                            </tr>
                            @php
                                $CWC += $item['CWC'];
                                $EMTC += $item['EMTC'];
                                $MLPF += $item['MLPF'];
                                $MBTC += $item['MBTC'];
                                $SPCF += $item['SPCF'];
                                $ODFLC += $item['ODFLC'];
                                $ComR += $item['ComR'];
                                $RSF += $item['RSF'];
                                $PVTS += $item['PVTS'];
                                $ASF += $item['ASF'];
                                $FOTHER += $item['FOTHER'];
                                $totalValue += $item['totalValue'];
                                $totalVat += $item['totalVat'];
                            @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold">
                            <td>TOTAL</td>
                            <td>{{ number_format($CWC, 2) }}</td>
                            <td>{{ number_format($EMTC, 2) }}</td>
                            <td>{{ number_format($MLPF, 2) }}</td>
                            <td>{{ number_format($MBTC, 2) }}</td>
                            <td>{{ number_format($SPCF, 2) }}</td>
                            <td>{{ number_format($ODFLC, 2) }}</td>
                            <td>{{ number_format($ComR, 2) }}</td>
                            <td>{{ number_format($RSF, 2) }}</td>
                            <td>{{ number_format($PVTS, 2) }}</td>
                            <td>{{ number_format($ASF, 2) }}</td>
                            <td>{{ number_format($FOTHER, 2) }}</td>
                            <td>{{ number_format($totalValue, 2) }}</td>
                            <td>{{ number_format($totalVat, 2) }}</td>
                        </tr>
                    </tfoot>

                </table>
            @endforeach

        </div>
    </div>
</div>