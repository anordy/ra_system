<div class="card">
    <div class="card-body">
        <div class="card-header">RESTAURANT LEVY ATTACHMENT</div>
        <table class="table table-bordered table-sm normal-text">
            <thead>
                <tr>
                    <th class="text-center">DATE</th>
                    <th class="text-center">FOOD SALES</th>
                    <th class="text-center">BEVERAGE SALES (SOFT DRINKS)</th>
                    <th class="text-center">BAR SALES (HARD DRINKS)</th>
                    <th class="text-center">OTHER SALES</th>
                    <th class="text-center">TOTAL REVENUE</th>
                </tr>
            </thead>

            <tbody>
                @if (count($return->restaurantAttachment ?? []))
                    @foreach ($return->restaurantAttachment as $index => $details)
                            <tr>
                                <th class="text-center">{{ $details['no_of_days'] }}</th>
                                
                                <td class="text-right">{{ number_format($details['food_sales'], 2) }}</td>
                                <td class="text-right">{{ number_format($details['beverage_sales'], 2) }}</td>
                                <td class="text-right">{{ number_format($details['bar_sales'], 2) }}</td>

                                <td class="text-right">{{ number_format($details['other_sales'], 2) }}</td>
                                <td class="text-right">{{ number_format($details['total_revenue'], 2) }}</td>
                            </tr>
                    @endforeach
                    <tr>
                        <td class="text-right"><strong>Total:</strong></td>
                        <td class="text-right"><strong>{{ number_format($return->restaurantAttachment->sum('food_sales'), 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($return->restaurantAttachment->sum('beverage_sales'), 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($return->restaurantAttachment->sum('bar_sales'), 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($return->restaurantAttachment->sum('other_sales'), 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($return->restaurantAttachment->sum('total_revenue'), 2) }}</strong></td>
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
