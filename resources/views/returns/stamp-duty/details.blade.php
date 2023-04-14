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
                @if($item->config->code == 'WITHH')
                    <td class="bg-secondary"></td>
                @else
                    <td>{{ number_format($item->value, 2) }}</td>
                @endif
                @if($item->config->rate_applicable)
                    <td>
                        {{ $item->config->rate_type === 'percentage' ? $item->config->rate . '%' : $item->config->rate_usd }}
                    </td>
                @else
                    <td class="bg-secondary"></td>
                @endif
                @if($item->config->is_summable)
                    <td>{{ number_format($item->vat, 2) }}</td>
                @else
                    <td class="bg-secondary"></td>
                @endif
            </tr>
        @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th style="width: 20%"></th>
        <th style="width: 30%"></th>
        <th style="width: 25%"></th>
        <th style="width: 25%">{{ number_format($return->total_amount_due, 2) }}</th>
    </tr>

    </tfoot>
</table>

