<table style="border-collapse:collapse; width:100%">
    <thead>
    <tr>
        <th>
            <p style="margin-bottom: 0px; margin-top: 25px; text-align: left; font-size: 18px; text-transform: uppercase">
                Return Details</p>
        </th>
    </tr>
    </thead>
</table>
<table class="tbl-bordered tbl-p-6" style="width: 100%; margin-top: 10px;">
    <thead>
    <th>Item Name</th>
    <th>Number of Liters/ Value</th>
    <th>Rate per Liter</th>
    <th>Amount ({{ $return->currency  }})</th>
    </thead>
    <tbody>
    @foreach ($return->configReturns as $item)
        @if($item->config->col_type == 'heading')
        @elseif($item->config->code === 'MSP' && $item->config->rate == 300 && $item->vat == 0)
            )
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
                            {{ $item->config->rate == 0 ? 1 : $item->config->rate }} {{ $item->config->currency ?? 'N/A' }}
                        @endif
                    @endif
                </td>
                <td>{{ number_format($item->vat, 1) }}</td>
            </tr>
        @else
            <tr>
                <td>{{ $item->config->name ?? 'name' }}</td>
                <td>{{ number_format($item->value, 2) }}</td>
                <td>
                    @if($item->config->rate_type === 'percentage')
                        {{ $item->config->rate }}%
                    @elseif($item->config->rate_type === 'fixed')
                        @if($item->config->rate_usd)
                            {{ number_format($item->config->rate_usd, 2) }} USD
                        @else
                            {{ $item->config->rate == 0 ? 1 : $item->config->rate }} {{ $item->config->currency ?? 'N/A' }}
                        @endif
                    @endif
                </td>
                <td>{{ number_format($item->vat, 1) }}</td>
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