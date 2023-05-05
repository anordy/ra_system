<div class="col-md-12">
    <h6 class="text-uppercase mt-4 mb-2 font-weight-bold">Stamp duty return details</h6>
    <table class="table table-bordered">
        <thead>
        <th style="width: 30%">Item Name</th>
        <th style="width: 20%">Value</th>
        <th style="width: 10%">Rate</th>
        <th style="width: 20%">TAX</th>
        </thead>
        <tbody>
        @foreach ($return->items as $item)
            @if($item->config->col_type === 'heading')
                <tr class="font-weight-bold">
                    @foreach($item->config->headings as $heading)
                        <th>{{ $heading['name'] }}</th>
                    @endforeach
                </tr>
            @else
                <tr>
                    <td>{{ $item->config->name ?? 'name' }}</td>
                    @if($item->config->code == 'WITHH')
                        <td class="bg-secondary"></td>
                    @else
                        <td>{{ number_format($item->value, 2) }}</td>
                    @endif
                    @if($item->config->rate_applicable)
                    <td>
                        @if ($item->config->rate_type == 'percentage')
                            {{ $item->config->rate }} %
                        @elseif ($item->config->rate_type == 'fixed')
                            @if ($item->config->currency == 'TZS')
                                {{ $item->config->rate }} {{ $item->config->currency }}
                            @elseif ($item->config->currency == 'USD')
                            {{ $item->config->rate_usd }} {{ $item->config->currency }}
                            @endif
                        @endif
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
            @endif
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th style="width: 20%">Total Amount Without Penalties</th>
            <th style="width: 30%"></th>
            <th style="width: 25%"></th>
            <th style="width: 25%">{{ number_format($return->total_amount_due, 2) }}</th>
        </tr>
        </tfoot>
    </table>
</div>
@if($return->withheld_certificate)
    <div class="col-md-3">
        <a class="file-item"  target="_blank"  href="{{ route('returns.stamp-duty.withheld-certificate', encrypt($return->id)) }}">
            <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
            <div style="font-weight: 500;" class="ml-1">
                Withheld Certificate
            </div>
            <i class="bi bi-arrow-up-right-square ml-2"></i>
        </a>
    </div>
@endif
<div class="col-md-12">
    <h6 class="text-uppercase mt-4 mb-2 font-weight-bold">Penalties</h6>
    <table class="table table-bordered table-sm normal-text">
        <thead>
            <tr>
                <th>Month</th>
                <th>Tax Amount</th>
                <th>Late Filing Amount</th>
                <th>Late Payment Amount</th>
                <th>Interest Rate</th>
                <th>Interest Amount</th>
                <th>Payable Amount</th>
            </tr>
        </thead>

        <tbody>
            @if(count($return->penalties))
                @foreach ($return->penalties as $penalty)
                <tr>
                    <td>{{ $penalty['financial_month_name'] }}</td>
                    <td>{{ number_format($penalty['tax_amount'], 2) }} <strong>{{ $return->currency}}</strong></td>
                    <td>{{ number_format($penalty['late_filing'], 2) }} <strong>{{ $return->currency}}</strong></td>
                    <td>{{ number_format($penalty['late_payment'], 2) }} <strong>{{ $return->currency}}</strong></td>
                    <td>{{ number_format($penalty['rate_percentage'], 4) }}</td>
                    <td>{{ number_format($penalty['rate_amount'], 2) }} <strong>{{ $return->currency}}</strong></td>
                    <td>{{ number_format($penalty['penalty_amount'], 2)}} <strong>{{ $return->currency}}</strong></td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center py-3">
                        No penalties for this return.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

