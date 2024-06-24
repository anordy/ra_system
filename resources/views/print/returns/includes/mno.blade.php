<table style="border-collapse:collapse; width:100%">
    <thead>
    <tr>
        <th>
            <p style="margin-bottom: 0px; margin-top: 25px; text-align: left; font-size: 18px; text-transform: uppercase">Return Details</p>
        </th>
    </tr>
    </thead>
</table>
<table class="tbl-bordered tbl-p-6" style="width: 100%; margin-top: 10px;">
    <thead>
        <th>Item Name</th>
        <th>Value ({{ $return->currency }})</th>
        <th>Rate</th>
        <th>Tax ({{ $return->currency  }})</th>
    </thead>
    <tbody>
    @foreach ($return->items as $item)
        @if($item->config->code != 'TOTAL')
            <tr>
                <td>{{ $item->config->name }}</td>
                <td>{{ number_format($item->value, 2) }}</td>
                <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate . '%' . '%' : $item->config->rate_usd .''. $item->config->currency }}</td>
                <td>{{ number_format($item->vat, 2) }}</td>
            </tr>
        @endif
    @endforeach
    </tbody>
    <tfoot>
    <tr class="bg-secondary">
        <th>Total</th>
        <th></th>
        <th></th>
        <th>{{ number_format($return->total_amount_due, 2) }}</th>
    </tr>
    </tfoot>
</table>