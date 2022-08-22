<div class="card">
    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Declared Sales Analysis</h6>
        <hr>
        <div class="row mx-2">
            @foreach ($returns as $year => $return)
            <strong class="px-2">{{ $year }}</strong>
                @php
                    $MSP = 0;
                    $GO = 0;
                    $IK = 0;
                    $JET = 0;
                    $PTL = 0;
                    $IFT = 0;
                    $RDF = 0;
                    $RLF = 0;
                    $HEADING1 = 0;
                    $EXP = 0;
                    $LOP = 0;
                    $IMP = 0;
                    $totalValue = 0;
                    $totalVat = 0;
                @endphp
                <table class="table table-sm table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>Month</th>
                            @foreach ($headersPetroleum as $header)
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
                                <td>{{ $item['MSP'] }}</td>
                                <td>{{ $item['GO'] }}</td>
                                <td>{{ $item['IK'] }}</td>
                                <td>{{ $item['JET'] }}</td>
                                <td>{{ $item['PTL'] }}</td>
                                <td>{{ $item['IFT'] }}</td>
                                <td>{{ $item['RDF'] }}</td>
                                <td>{{ $item['RLF'] }}</td>
                                <td>{{ $item['HEADING1'] }}</td>
                                <td>{{ $item['EXP'] }}</td>
                                <td>{{ $item['LOP'] }}</td>
                                <td>{{ $item['IMP'] }}</td>
                                <td>{{ $item['totalValue'] }}</td>
                                <td>{{ $item['totalVat'] }}</td>
                            </tr>
                            @php
                                $MSP += $item['MSP'];
                                $GO += $item['GO'];
                                $IK += $item['IK'];
                                $JET += $item['JET'];
                                $PTL += $item['PTL'];
                                $IFT += $item['IFT'];
                                $RDF += $item['RDF'];
                                $RLF += $item['RLF'];
                                $HEADING1 += $item['HEADING1'];
                                $EXP += $item['EXP'];
                                $LOP += $item['LOP'];
                                $IMP += $item['IMP'];
                                $totalValue += $item['totalValue'];
                                $totalVat += $item['totalVat'];
                            @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold">
                            <td>TOTAL</td>
                            <td>{{ number_format($MSP, 2) }}</td>
                            <td>{{ number_format($GO, 2) }}</td>
                            <td>{{ number_format($IK, 2) }}</td>
                            <td>{{ number_format($JET, 2) }}</td>
                            <td>{{ number_format($PTL, 2) }}</td>
                            <td>{{ number_format($IFT, 2) }}</td>
                            <td>{{ number_format($RDF, 2) }}</td>
                            <td>{{ number_format($RLF, 2) }}</td>
                            <td>{{ number_format($HEADING1, 2) }}</td>
                            <td>{{ number_format($EXP, 2) }}</td>
                            <td>{{ number_format($LOP, 2) }}</td>
                            <td>{{ number_format($IMP, 2) }}</td>
                            <td>{{ number_format($totalValue, 2) }}</td>
                            <td>{{ number_format($totalVat, 2) }}</td>
                        </tr>
                    </tfoot>

                </table>
            @endforeach

        </div>
    </div>
</div>
