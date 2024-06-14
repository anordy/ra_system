<div class="card">
    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Filed Return Details for {{ $return->financialMonth->name }},
            {{ $return->financialMonth->year->code }}</h6>
        <hr>
        <div class="row">
            @if ($return->configReturns)
                <div class="col-md-12">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <th style="width: 30%">Item Name(TZS)</th>
                            <th style="width: 20%">Value</th>
                            <th style="width: 10%">Rate</th>
                            <th style="width: 20%">VAT</th>
                        </thead>
                        <tbody>
                            @foreach ($return->configReturns as $item)
                                <tr>
                                    <td>{{ $item->config->name ?? 'name' }}</td>
                                    <td>{{ number_format($item->value, 2) }}</td>
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
                                    <td>{{ number_format($item->vat, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            @endif

            @if (isset($return_) && $return_->configReturns)
            <div class="col-md-12">
                <p class="text-uppercase font-weight-bold">{{ __('Return Items') }} (USD)</p>
            </div>
            <div class="col-md-12">
                <table class="table table-bordered table-striped normal-text">
                    <thead>
                        <th style="width: 30%">{{ __('Item Name') }}</th>
                        <th style="width: 20%">{{ __('Value') }}</th>
                        <th style="width: 10%">{{ __('Rate') }}</th>
                        <th style="width: 20%">{{ __('Tax') }}</th>
                    </thead>
                    <tbody>
                        @foreach ($return_->configReturns as $item)
                            <tr>
                                <td>{{ $item->config->name }}</td>
                                <td>{{ number_format($item->value, 2) }}</td>
                                <td>
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
                                        {{ $item->config->rate }} %
                                    @endif
                                    {{-- {{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }} --}}
                                </td>
                                <td>{{ number_format($item->vat, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif


            <div class="col-md-12">
                <h6 class="text-uppercase mt-2 ml-2">Penalties</h6>
                <hr>
                <table class="table table-bordered table-sm normal-text">
                    <label>Penalties</label>
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
                                        <strong>{{ $penalty->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['late_filing'], 2) }}
                                        <strong>{{ $penalty->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['late_payment'], 2) }}
                                        <strong>{{ $penalty->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['rate_percentage'], 4) }}</td>
                                    <td>{{ number_format($penalty['rate_amount'], 2) }}
                                        <strong>{{ $penalty->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['penalty_amount'], 2) }}
                                        <strong>{{ $penalty->currency }}</strong>
                                    </td>
                                </tr>
                            @endforeach

                             {{-- @foreach ($return->penalties->where('currency', 'USD') as $penalty)
                                <tr>
                                    <td>{{ $penalty['financial_month_name'] }}</td>
                                    <td>{{ number_format($penalty['tax_amount'], 2) }}
                                        <strong>{{ $penalty->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['late_filing'], 2) }}
                                        <strong>{{ $penalty->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['late_payment'], 2) }}
                                        <strong>{{ $penalty->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['rate_percentage'], 4) }}</td>
                                    <td>{{ number_format($penalty['rate_amount'], 2) }}
                                        <strong>{{ $penalty->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['penalty_amount'], 2) }}
                                        <strong>{{ $penalty->currency }}</strong>
                                    </td>
                                </tr>
                            @endforeach --}}
                        @else
                            <tr>
                                <td colspan="7" class="text-center py-3">
                                    No penalties for this return.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <table class="table table-bordered table-sm normal-text">
                    <label>USD Penalties</label>
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
                        @if (isset($return_) && count($return_->penalties))
                            @foreach ($return_->penalties->where('currency', 'USD') as $penalty)
                                <tr>
                                    <td>{{ $penalty['financial_month_name'] }}</td>
                                    <td>{{ number_format($penalty['tax_amount'], 2) }}
                                        <strong>{{ $penalty->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['late_filing'], 2) }}
                                        <strong>{{ $penalty->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['late_payment'], 2) }}
                                        <strong>{{ $penalty->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['rate_percentage'], 4) }}</td>
                                    <td>{{ number_format($penalty['rate_amount'], 2) }}
                                        <strong>{{ $penalty->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['penalty_amount'], 2) }}
                                        <strong>{{ $penalty->currency }}</strong>
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
