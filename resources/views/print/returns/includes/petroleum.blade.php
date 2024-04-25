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
    <th style="width: 30%">Item Name</th>
    <th style="width: 20%">Value ({{ $return->currency }})</th>
    <th style="width: 10%">Rate</th>
    <th style="width: 20%">Tax ({{ $return->currency  }})</th>
    </thead>
    <tbody>
    @foreach ($return->configReturns as $item)
        <tr>
            <td>{{ $item->config->name ?? 'name' }}</td>
            <td>{{ number_format($item->value, 2) }}</td>
            <td>
                @if($item->config->rate_type === 'percentage')
                    {{ $item->config->rate }}%
                @elseif($item->config->rate_type === 'fixed')
                    @if($item->config->rate_usd)
                        {{ $item->config->rate_usd }} USD
                    @else
                        {{ $item->config->rate }}
                    @endif
                @endif
            </td>
            <td>{{ number_format($item->vat, 1) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr class="bg-secondary">
        <th style="width: 20%">Total</th>
        <th style="width: 30%"></th>
        <th style="width: 25%"></th>
        <th style="width: 25%">{{ number_format($return->total_amount_due, 2) }}</th>
    </tr>
    </tfoot>
</table>