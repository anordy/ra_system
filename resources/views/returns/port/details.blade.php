<div class="card">
    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Filled Return Details for {{ $return->financialMonth->name }},
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
                                    <td>{{ number_format($item->value) }}</td>
                                    <td>{{ $item->config->rate_type ?? '' === 'percentage' ? $item->config->rate ?? '' : $item->config->rate_usd ?? '' }}
                                    </td>
                                    <td>{{ number_format($item->vat) }}</td>
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

                {{-- <table class="table table-bordered table-sm normal-text">
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
                        @if (count($return_->penalties))
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
                </table> --}}
            </div>
        </div>
    </div>
</div>
