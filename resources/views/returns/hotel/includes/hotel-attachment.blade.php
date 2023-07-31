<div class="card">
    <div class="card-body">
        <div class="card-header">HOTEL LEVY ATTACHMENT</div>
        <table class="table table-bordered table-sm normal-text">
            <thead>
                <tr>
                    <th class="text-center">DATE</th>
                    <th class="text-center" colspan="3">NO. OF PAX</th>
                    <th class="text-center">TOTAL ROOM REVENUE</th>
                    <th class="text-center" colspan="2">REVENUE</th>
                    <th class="text-center">TOTAL REVENUE OF FOOD BEVARAGE</th>
                    <th class="text-center">OTHER REVENUE</th>
                </tr>
                <tr>
                    <th></th>
                    <th class="text-center">R</th>
                    <th class="text-center">NR</th>
                    <th class="text-center">Total Pax</th>
                    <th></th>
                    <th class="text-center">FOOD</th>
                    <th class="text-center">BEVERAGE</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @if (count($return->hotelLevyAttachment ?? []))
                    @php
                        $totalPaxR = 0;
                        $totalPaxNR = 0;
                        $totalPax = 0;
                        $totalRoomRevenue = 0;
                        $revenueForFood = 0;
                        $revenueForBeverage = 0;
                        $totalRevenueOfFoodBeverage = 0;
                        $otherRevenue = 0;
                    @endphp
                    @foreach ($return->hotelLevyAttachment as $index => $details)
                        <tr>
                            <th class="text-center">{{ $details['no_of_days'] }}</th>

                            <td class="text-center">{{ $details['no_of_pax_for_r'] }}</td>
                            <td class="text-center">{{ $details['no_of_pax_for_nr'] }}</td>
                            <td class="text-center">{{ $details['total_no_of_pax'] }}</td>

                            <td class="text-right">{{ number_format($details['total_room_revenue'], 2) }}</td>

                            <td class="text-right">{{ number_format($details['revenue_for_food'], 2) }}</td>
                            <td class="text-right">{{ number_format($details['revenue_for_beverage'], 2) }}</td>

                            <td class="text-right">{{ number_format($details['total_revenue_of_food_beverage'], 2) }}
                            </td>
                            <td class="text-right">{{ number_format($details['other_revenue'], 2) }}</td>
                        </tr>
                        @php
                            $totalPaxR += $details['no_of_pax_for_r'];
                            $totalPaxNR += $details['no_of_pax_for_nr'];
                            $totalPax += $details['total_no_of_pax'];
                            $totalRoomRevenue += $details['total_room_revenue'];
                            $revenueForFood += $details['revenue_for_food'];
                            $revenueForBeverage += $details['revenue_for_beverage'];
                            $totalRevenueOfFoodBeverage += $details['total_revenue_of_food_beverage'];
                            $otherRevenue += $details['other_revenue'];
                        @endphp
                    @endforeach
                    <tr>
                        <td class="text-right"><strong>Total:</strong></td>
                        <td class="text-right"><strong>{{ number_format($totalPaxR, 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($totalPaxNR, 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($totalPax, 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($totalRoomRevenue, 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($revenueForFood, 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($revenueForBeverage, 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($totalRevenueOfFoodBeverage, 2) }}</strong>
                        </td>
                        <td class="text-right"><strong>{{ number_format($otherRevenue, 2) }}</strong></td>
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
