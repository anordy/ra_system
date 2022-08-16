<div class="card">
    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Return Analysis</h6>
        <hr>

        @foreach ($returns as $year => $return)
            <strong>{{ $year }}</strong>
            @php
                $PM = 0;
                $AWP = 0;
                $TP = 0;
            @endphp

            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Quater</th>
                        <th>Principal Tax Amount</th>
                        <th>Penalties</th>
                        <th>Principal Amount + penalties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($return as $item)
                        <tr>
                            <td>{{ $item['quarter'] }}</td>
                            <td>{{ number_format($item['principalAmount'], 2) }}</td>
                            <td>{{ number_format($item['Penalties']), 2 }}</td>
                            <td>{{ number_format($item['amountWithPenalties'], 2) }}</td>

                        </tr>
                        @php
                            $PM += $item['principalAmount'];
                            $AWP += $item['amountWithPenalties'];
                            $TP += $item['Penalties'];
                            
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold">
                        <td>TOTAL</td>
                        <td>{{ number_format($PM, 2) }}</td>
                        <td>{{ number_format($AWP, 2) }}</td>
                        <td>{{ number_format($TP, 2) }}</td>

                    </tr>
                </tfoot>

            </table>
        @endforeach

    </div>
</div>
