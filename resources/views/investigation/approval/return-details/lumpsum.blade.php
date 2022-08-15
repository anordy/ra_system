<div class="card">
    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Return Analysis</h6>
        <hr>

        @foreach ($returns as $year => $return)
            <strong>{{ $year }}</strong>
            @php
                $MSP = 0;
                $GO = 0;
                $IK = 0;
            @endphp

            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th>Quater</th>
                        <th>Principal Tax Amount</th>
                        <th>Amount With penalties</th>
                        <th>Penalties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($return as $item)
                        <tr>
                            <td>{{ $item['quarter'] }}</td>
                            <td>{{ number_format($item['principalAmount'], 2) }}</td>
                            <td>{{ number_format($item['amountWithPenalties'], 2) }}</td>
                            <td>{{ number_format($item['Penalties']), 2 }}</td>

                        </tr>
                        @php
                            $MSP += $item['principalAmount'];
                            $GO += $item['amountWithPenalties'];
                            $IK += $item['Penalties'];
                            
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold">
                        <td>TOTAL</td>
                        <td>{{ number_format($MSP, 2) }}</td>
                        <td>{{ number_format($GO, 2) }}</td>
                        <td>{{ number_format($IK, 2) }}</td>

                    </tr>
                </tfoot>

            </table>
        @endforeach

    </div>
</div>
