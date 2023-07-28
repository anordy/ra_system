<div class="card">
    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Filled {{ $return->taxType->name }} Return Details for
            {{ $return->financialMonth->name }}, {{ $return->financialMonth->year->code }}</h6>
        <hr>
        @if ($return->taxType->code == \App\Models\TaxType::HOTEL || $return->taxType->code == \App\Models\TaxType::AIRBNB)
            <div class="row">
                @foreach ($return->items as $item)
                    @if (in_array($item->config->col_type, ['hotel_top', 'hotel_bottom']))
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ $item->config->name }}</span>
                            <p class="my-1">{{ number_format($item->value) }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
        <div class="row">
            <div class="col-md-12 mb-4">
                <table class="table table-bordered mb-0 normal-text">
                    <thead>
                        <tr class="table-active">
                            <th>Supplies of goods & services / Mauzo ya bidhaa na/au huduma</th>
                            <th>Value (Excluding Tax) / Thamani bila ya kodi</th>
                            <th>Rate / Kiwango</th>
                            <th>Tax Amount (Kiasi cha Kodi)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($return->items as $item)
                            @if ($item->config->heading_type == 'supplies')
                                <tr>
                                    <td class="return-label">{{ $item->config->name }}</td>
                                    <td class="return-label">
                                        {{ number_format($item->value, 2) }}
                                    </td>
                                    <td class="@if ($item->config->rate_usd == 0 && $item->config->rate == 0) table-active @endif return-label">
                                        @if ($item->config->rate_type == 'fixed')
                                            @if ($item->config->currency == 'both')
                                                {{ $item->config->rate }} TZS <br>
                                                {{ $item->config->rate_usd }} USD
                                            @elseif ($item->config->currency == 'TZS')
                                                {{ $item->config->rate }} TZS
                                            @elseif ($item->config->currency == 'USD')
                                                {{ $item->config->rate_usd }} USD
                                            @endif
                                        @elseif ($item->config->rate_type == 'percentage')
                                            {{ $item->config->rate }}%
                                        @endif
                                    </td>
                                    <td class="@if ($item->config->rate_usd == 0 && $item->config->rate == 0) table-active @endif return-label">
                                        {{ number_format($item->vat, 2) }} {{ $return->currency }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                    <thead>
                        <tr class="table-active">
                            <th>Purchases / Manunuzi</th>
                            <th>Value of Purchases / Thamani ya Manunuzi</th>
                            <th>Rate / Kiwango</th>
                            <th>Tax Amount (Kiasi cha Kodi)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($return->items as $item)
                            @if ($item->config->heading_type == 'purchases')
                                <tr>
                                    <td class="return-label">{{ $item->config->name }}</td>
                                    <td class="return-label">
                                        {{ number_format($item->value, 2) }}
                                    </td>
                                    <td class="@if ($item->config->rate_usd == 0 && $item->config->rate == 0) table-active @endif return-label">
                                        @if ($item->config->rate_type == 'fixed')
                                            @if ($item->config->currency == 'both')
                                                {{ $item->config->rate }} TZS <br>
                                                {{ $item->config->rate_usd }} USD
                                            @elseif ($item->config->currency == 'TZS')
                                                {{ $item->config->rate }} TZS
                                            @elseif ($item->config->currency == 'USD')
                                                {{ $item->config->rate_usd }} USD
                                            @endif
                                        @elseif ($item->config->rate_type == 'percentage')
                                            {{ $item->config->rate }}%
                                        @endif
                                    </td>
                                    <td class="@if ($item->config->rate_usd == 0 && $item->config->rate == 0) table-active @endif return-label">
                                        {{ number_format($item->vat, 2) }} {{ $return->currency }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-secondary">
                            <th style="width: 20%">{{ __('Total') }}</th>
                            <th style="width: 30%"></th>
                            <th style="width: 25%"></th>
                            <th style="width: 25%">{{ number_format($return->total_amount_due) }}
                                {{ $return->currency }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="col-md-12">
                <h6 class="text-uppercase mt-2 ml-2">Penalties</h6>
                <hr>
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
                        @if (count($return->penalties))
                            @foreach ($return->penalties as $penalty)
                                <tr>
                                    <td>{{ $penalty['financial_month_name'] }}</td>
                                    <td>{{ number_format($penalty['tax_amount'], 2) }}
                                        <strong>{{ $return->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['late_filing'], 2) }}
                                        <strong>{{ $return->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['late_payment'], 2) }}
                                        <strong>{{ $return->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['rate_percentage'], 4) }}</td>
                                    <td>{{ number_format($penalty['rate_amount'], 2) }}
                                        <strong>{{ $return->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['penalty_amount'], 2) }}
                                        <strong>{{ $return->currency }}</strong>
                                    </td>
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
        </div>
    </div>
</div>
