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
                    <th class="text-center">TOTAL REVENUE OF FOOD BEVERAGE</th>
                    <th class="text-center">OTHER REVENUE</th>
                </tr>
                <tr>
                    <th></th>
                    <th class="text-center">R</th>
                    <th class="text-center">NR</th>
                    <th class="text-center">TOTAL PAX</th>
                    <th></th>
                    <th class="text-center">FOOD</th>
                    <th class="text-center">BEVERAGE</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @if (count($return->hotelLevyAttachment ?? []))
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
                    @endforeach
                    <tr>
                        <td class="text-right"><strong>Total:</strong></td>
                        <td class="text-right"><strong>{{ number_format($return->hotelLevyAttachment->sum('no_of_pax_for_r'), 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($return->hotelLevyAttachment->sum('no_of_pax_for_nr'), 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($return->hotelLevyAttachment->sum('total_no_of_pax'), 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($return->hotelLevyAttachment->sum('total_room_revenue'), 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($return->hotelLevyAttachment->sum('revenue_for_food'), 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($return->hotelLevyAttachment->sum('revenue_for_beverage'), 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($return->hotelLevyAttachment->sum('total_revenue_of_food_beverage'), 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($return->hotelLevyAttachment->sum('other_revenue'), 2) }}</strong></td>
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
