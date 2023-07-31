<div class="card">
        <div class="card-body">
        <div class="card-header">TOUR OPERATOR ATTACHMENT</div>
            <table class="table table-bordered table-sm normal-text">
                <thead>
                    <tr>
                        <th class="text-center">DATE</th>
                        <th class="text-center" colspan="3">NO. OF PAX</th>
                        <th class="text-center" colspan="2">REVENUE</th>
                        <th class="text-center">OTHER SERVICE</th>
                        <th class="text-center">TOTAL REVENUE</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th class="text-center">R</th>
                        <th class="text-center">NR</th>
                        <th class="text-center">Total Pax</th>
                        <th class="text-center">TRANSFER</th>
                        <th class="text-center">EXCUSSION</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @if (count($return->tourOperatorAttachment ?? []))
                    @php
                        $totalPaxR = 0;
                        $totalPaxNR = 0;
                        $totalPax = 0;
                        $revenueTransfer = 0;
                        $revenueExcussion = 0;
                        $otherService = 0;
                        $totalRevenue = 0;
                    @endphp
                        @foreach ($return->tourOperatorAttachment as $index => $details)
                                <tr>
                                    <th class="text-center">{{ $details['no_of_days'] }}</th>

                                    <td class="text-center">{{ $details['no_of_pax_for_r'] }}</td>
                                    <td class="text-center">{{ $details['no_of_pax_for_nr'] }}</td>
                                    <td class="text-center">{{ $details['total_no_of_pax'] }}</td>

                                    <td class="text-right">{{ number_format($details['revenue_transfer'], 2) }}</td>
                                    <td class="text-right">{{ number_format($details['revenue_excussion'], 2) }}</td>

                                    <td class="text-right">{{ number_format($details['other_service'], 2) }}</td>
                                    <td class="text-right">{{ number_format($details['total_revenue'], 2) }}</td>
                                </tr>
                                @php
                                    $totalPaxR += $details['no_of_pax_for_r'];
                                    $totalPaxNR += $details['no_of_pax_for_nr'];
                                    $totalPax += $details['total_no_of_pax'];
                                    $revenueTransfer += $details['revenue_transfer'];
                                    $revenueExcussion += $details['revenue_excussion'];
                                    $otherService += $details['other_service'];
                                    $totalRevenue += $details['total_revenue'];
                                @endphp
                        @endforeach
                        <tr>
                            <td class="text-right"><strong>Total:</strong></td>
                            <td class="text-right"><strong>{{ number_format($totalPaxR, 2) }}</strong></td>
                            <td class="text-right"><strong>{{ number_format($totalPaxNR, 2) }}</strong></td>
                            <td class="text-right"><strong>{{ number_format($totalPax, 2) }}</strong></td>
                            <td class="text-right"><strong>{{ number_format($revenueTransfer, 2) }}</strong></td>
                            <td class="text-right"><strong>{{ number_format($revenueExcussion, 2) }}</strong></td>
                            <td class="text-right"><strong>{{ number_format($otherService, 2) }}</strong></td>
                            <td class="text-right"><strong>{{ number_format($totalRevenue, 2) }}</strong>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="7" class="text-center py-3">
                                No data.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
</div>
