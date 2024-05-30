<div class="card p-0 m-0">
    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Filed Return Details For {{ $return->taxtype->name ?? 'N/A' }}</h6>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-sm">
                    <thead>
                    <th class="w-30">Item Name</th>
                    <th class="w-20">Value</th>
                    <th class="w-10">Rate</th>
                    <th class="w-20">VAT</th>
                    </thead>
                    <tbody>
                    @if(!empty($return->configReturns))
                        @foreach ($return->configReturns as $item)
                            @if($item->config->col_type == 'total')
                                <tr>
                                    <td colspan="3">{{ $item->config->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($item->vat, 2) }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td>{{ $item->config->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($item->value, 2) }}</td>
                                    <td>
                                        @if ($item->config->rate_type == 'percentage')
                                            {{ $item->config->rate ?? 'N/A' }} %
                                        @elseif ($item->config->rate_type == 'fixed')
                                            @if ($item->config->currency == 'TZS')
                                                {{ $item->config->rate ?? 'N/A' }} {{ $item->config->currency ?? 'N/A' }}
                                            @elseif ($item->config->currency == 'USD')
                                                {{ $item->config->rate_usd ?? 'N/A' }} {{ $item->config->currency ?? 'N/A' }}
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->vat, 2) }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                    </tbody>
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
                    @if(count($return->penalties))
                        @foreach ($return->penalties as $penalty)
                            <tr>
                                <td>{{ $penalty['financial_month_name'] }}</td>
                                <td>{{ number_format($penalty['tax_amount'], 2) }}
                                    <strong>{{ $return->currency}}</strong></td>
                                <td>{{ number_format($penalty['late_filing'], 2) }}
                                    <strong>{{ $return->currency}}</strong></td>
                                <td>{{ number_format($penalty['late_payment'], 2) }}
                                    <strong>{{ $return->currency}}</strong></td>
                                <td>{{ number_format($penalty['rate_percentage'], 4) }}</td>
                                <td>{{ number_format($penalty['rate_amount'], 2) }}
                                    <strong>{{ $return->currency}}</strong></td>
                                <td>{{ number_format($penalty['penalty_amount'], 2)}}
                                    <strong>{{ $return->currency}}</strong></td>
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
