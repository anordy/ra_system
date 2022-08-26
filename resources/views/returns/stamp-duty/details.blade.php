<table class="table table-bordered">
    <thead>
    <th style="width: 30%">Item Name</th>
    <th style="width: 20%">Value</th>
    <th style="width: 10%">Rate</th>
    <th style="width: 20%">VAT</th>
    </thead>
    <tbody>
    @foreach ($return->items as $item)
        <tr>
            <td>{{ $item->config->name ?? 'name' }}</td>
            <td>{{ number_format($item->value) }}</td>
            <td>{{ $item->config->rate_type ?? '' === 'percentage' ? $item->config->rate ?? '' : $item->config->rate_usd ?? '' }}
            </td>
            <td>{{ number_format($item->tax) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th style="width: 20%"></th>
        <th style="width: 30%"></th>
        <th style="width: 25%"></th>
        <th style="width: 25%">{{ number_format($return->total_amount_due) }}</th>
    </tr>

    </tfoot>
</table>

