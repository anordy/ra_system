<div class="card">
    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Declared Sales Analysis</h6>
        <hr>
        <div class="row mx-2">
            @foreach ($returns as $year => $return)
            <strong class="px-2">{{ $year }}</strong>
            @php
            $MNOS = 0;
            $MVNOS = 0;
            $MCPRE = 0;
            $MCPOST = 0;
            $MM = 0;
            $OFS = 0;
            $OES = 0;
            $totalValue = 0;
            $totalVat = 0;
            @endphp
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Month</th>
                        @foreach ($headersMno as $header)
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
                        <td>{{ $item['MNOS'] }}</td>
                        <td>{{ $item['MVNOS'] }}</td>
                        <td>{{ $item['MCPRE'] }}</td>
                        <td>{{ $item['MCPOST'] }}</td>
                        <td>{{ $item['MM'] }}</td>
                        <td>{{ $item['OFS'] }}</td>
                        <td>{{ $item['OES'] }}</td>
                        <td>{{ $item['totalValue'] }}</td>
                        <td>{{ $item['totalVat'] }}</td>
                    </tr>
                    @php
                    $MNOS += $item['MNOS'];
                    $MVNOS += $item['MVNOS'];
                    $MCPRE += $item['MCPRE'];
                    $MCPOST += $item['MCPOST'];
                    $MM += $item['MM'];
                    $OFS += $item['OFS'];
                    $OES += $item['OES'];
                    $totalValue += $item['totalValue'];
                    $totalVat += $item['totalVat'];
                    @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold">
                        <td>TOTAL</td>
                        <td>{{ number_format($MNOS, 2) }}</td>
                        <td>{{ number_format($MVNOS, 2) }}</td>
                        <td>{{ number_format($MCPRE, 2) }}</td>
                        <td>{{ number_format($MCPOST, 2) }}</td>
                        <td>{{ number_format($MM, 2) }}</td>
                        <td>{{ number_format($OFS, 2) }}</td>
                        <td>{{ number_format($OES, 2) }}</td>
                        <td>{{ number_format($totalValue, 2) }}</td>
                        <td>{{ number_format($totalVat, 2) }}</td>
                    </tr>
                </tfoot>

            </table>
            @endforeach

        </div>
    </div>
</div>