<div class="card p-0 m-0">
    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Filed Return Details For {{ $return->taxtype->name ?? 'N/A' }}</h6>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-responsive table-sm">
                    <thead>
                        <th>Item Name</th>
                        <th>Number of Litres/ Value</th>
                        <th>Rate per Litre</th>
                        <th>Amount ({{$return->currency}})</th>
                    </thead>
                    <tbody>
                    @if(!empty($return->configReturns))
                        @foreach ($return->configReturns as $item)
                            @if($item->config->col_type == 'heading')
                            @elseif($item->config->code === 'MSP' && $item->config->rate == 300 && $item->vat == 0)
                            @else
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
        </div>
    </div>
</div>
