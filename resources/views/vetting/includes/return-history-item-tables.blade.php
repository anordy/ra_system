<div class="card">
    <div class="card-body">
        <div class="row mt-2">
            <div class="col-md-6 text-uppercase">Filed Return Details</div>
            <div class="col-md-6 d-flex justify-content-end">
                <div>
                    <span class="font-weight-bold text-uppercase">Rejected At</span>
                    <p class="mt-1">
                        {{$history->created_at->format('d-m-Y H:i:s')}}
                    </p>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-sm">
                    <thead>
                    <th style="width: 30%">Item Name</th>
                    <th style="width: 20%">Value</th>
                    <th style="width: 10%">Rate</th>
                    <th style="width: 20%">VAT</th>
                    </thead>
                    <tbody>
                    @foreach (json_decode($history->return_items) as $item)
                        <tr>
                            <td>{{ $item->config->name ?? 'name' }}</td>
                            <td>{{ number_format($item->value, 2) }}</td>
                            <td>{{ $item->config->rate_type ?? '' === 'percentage' ? $item->config->rate ?? '' : $item->config->rate_usd ?? '' }}
                            </td>
                            <td>{{ number_format($item->vat, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th style="width: 20%"></th>
                        <th style="width: 30%"></th>
                        <th style="width: 25%"></th>
                        <th style="width: 25%">{{ number_format(json_decode($history->return_info)->total_amount, 2) }}</th>
                    </tr>

                    </tfoot>
                </table>

            </div>

            @if($history->penalties != 'null')
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
                        @if(count(json_decode($history->penalties)))
                            @foreach (json_decode($history->penalties) as $penalty)
                                <tr>
                                    <td>{{ $penalty->financial_month_name }}</td>
                                    <td>{{ number_format($penalty->tax_amount, 2) }}
                                        <strong>{{ $return->currency}}</strong></td>
                                    <td>{{ number_format($penalty->late_filing, 2) }}
                                        <strong>{{ $return->currency}}</strong></td>
                                    <td>{{ number_format($penalty->late_payment, 2) }}
                                        <strong>{{ $return->currency}}</strong></td>
                                    <td>{{ number_format($penalty->rate_percentage, 4) }}</td>
                                    <td>{{ number_format($penalty->rate_amount, 2) }}
                                        <strong>{{ $return->currency}}</strong></td>
                                    <td>{{ number_format($penalty->penalty_amount, 2)}}
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
            @endif
        </div>
    </div>
</div>
