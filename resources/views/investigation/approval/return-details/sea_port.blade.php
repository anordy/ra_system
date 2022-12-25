<div class="card">
    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Declared Sales Analysis</h6>
        <hr>
        <div class="row mx-2">
            @foreach ($returns as $year => $return)
                <strong class="px-2">{{ $year }}</strong>
                @php
                    // $NFAT = 0;
                    // $NLAT =0;
                    // $NFSF = 0;
                    // $NLSF = 0;
                    // $IT = 0;
                    $NFSP = 0;
                    $NLTM = 0;
                    $ITTM = 0;
                    $NLZNZ = 0;
                    $ITZNZ = 0;
                    $NSUS = 0;
                    $NSTZ = 0;
                    $totalValue = 0;
                    $totalVatTzs = 0;
                    $totalVatUsd = 0;
                @endphp
                <table class="table table-sm table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>Month</th>
                            {{-- {{ dd($headersPort) }} --}}
                            @foreach ($headersPort as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                            <th>Total</th>
                            <th>Output VAT(TZS)</th>
                            <th>Output VAT(USD)</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($return as $item)
                            <tr>
                                <td>{{ $item['month'] ?? 0 }}</td>
                                {{-- <td>{{ $item['NFAT'] ?? 0 }}</td>
                                <td>{{ $item['NLAT'] ?? 0 }}</td>
                                <td>{{ $item['NFSF'] ?? 0 }}</td>
                                <td>{{ $item['NLSF'] ?? 0 }}</td>
                                <td>{{ $item['IT'] ?? 0 }}</td> --}}
                                <td>{{ $item['NFSP'] ?? 0 }}</td>
                                <td>{{ $item['NLTM'] ?? 0 }}</td>
                                <td>{{ $item['ITTM'] ?? 0 }}</td>
                                <td>{{ $item['NLZNZ'] ?? 0 }}</td>
                                <td>{{ $item['ITZNZ'] ?? 0 }}</td>
                                <td>{{ $item['NSUS'] ?? 0 }}</td>
                                <td>{{ $item['NSTZ'] ?? 0 }}</td>
                                <td>{{ $item['totalValue'] }}</td>
                                <td>{{ number_format($item['totalVatTzs'],2) }}</td>
                                <td>{{ number_format($item['totalVatUsd'],2) }}</td>
                            </tr>
                            @php
                                // $NFAT += $item['NFAT'] ?? 0;
                                // $NLAT += $item['NLAT'] ?? 0;
                                // $NFSF += $item['NFSF'] ?? 0;
                                // $NLSF += $item['NLSF'] ?? 0;
                                // $IT += $item['IT'] ?? 0;
                                $NFSP += $item['NFSP'] ?? 0;
                                $NLTM += $item['NLTM'] ?? 0;
                                $ITTM += $item['ITTM'] ?? 0;
                                $NLZNZ += $item['NLZNZ'] ?? 0;
                                $ITZNZ += $item['ITZNZ'] ?? 0;
                                $NSUS += $item['NSUS'] ?? 0;
                                $NSTZ += $item['NSTZ'] ?? 0;
                                $totalValue += $item['totalValue'];
                                $totalVatTzs += $item['totalVatTzs'];
                                $totalVatUsd += $item['totalVatUsd'];
                            @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold">
                            <td>TOTAL</td>
                            {{-- <td>{{ number_format($NFAT, 2) }}</td>
                            <td>{{ number_format($NLAT, 2) }}</td>
                            <td>{{ number_format($NFSF, 2) }}</td>
                            <td>{{ number_format($NLSF, 2) }}</td>
                            <td>{{ number_format($IT, 2) }}</td> --}}
                            <td>{{ number_format($NFSP, 2) }}</td>
                            <td>{{ number_format($NLTM, 2) }}</td>
                            <td>{{ number_format($ITTM, 2) }}</td>
                            <td>{{ number_format($NLZNZ, 2) }}</td>
                            <td>{{ number_format($ITZNZ, 2) }}</td>
                            <td>{{ number_format($NSUS, 2) }}</td>
                            <td>{{ number_format($NSTZ, 2) }}</td>
                            <td>{{ number_format($totalValue, 2) }}</td>
                            <td>{{ number_format($totalVatTzs, 2) }}</td>
                            <td>{{ number_format($totalVatUsd, 2) }}</td>
                        </tr>
                    </tfoot>

                </table>
            @endforeach

        </div>
    </div>
</div>
