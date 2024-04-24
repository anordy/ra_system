<div class="col-md-12">
    <h6 class="text-uppercase mt-4 mb-2 font-weight-bold">Stamp duty return details</h6>
    <table class="table table-bordered table-responsive">
        <thead>
        <th class="w-30">Item Name</th>
        <th class="w-20">Value</th>
        <th class="w-10">Rate</th>
        <th class="w-20">TAX</th>
        </thead>
        <tbody>
        @if(!empty($return->items))
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
        @endif
        </tbody>
        <tfoot>
        <tr>
            <th class="w-20">Total Amount Without Penalties</th>
            <th class="w-30"></th>
            <th class="w-25"></th>
            <th class="w-25">{{ number_format($return->total_amount_due, 2) }}</th>
        </tr>
        </tfoot>
    </table>
</div>

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

<div class="col-md-12">
    <h6 class="text-uppercase mt-4 mb-2 font-weight-bold">Withheld Summary & Attachment</h6>
    @if($return->withheld_certificates_summary)
        <table class="table table-bordered table-striped normal-text">
            <thead>
            <tr>
                <th width="10">{{ __('SN') }}</th>
                <th>{{ __('Withholding Receipt No.') }}</th>
                <th>{{ __('Withholding Receipt Date') }}</th>
                <th>{{ __('Agent Name') }}</th>
                <th>{{ __('Agent No') }}</th>
                <th>{{ __('VFMS Receipt No') }}</th>
                <th>{{ __('VFMS Receipt Date') }}</th>
                <th>{{ __('Net Amount') }}</th>
                <th>{{ __('Tax Withheld') }}</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($return->withheld))
                @foreach($return->withheld as $withheld)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $withheld->withholding_receipt_no }}</td>
                        <td>{{ $withheld->withholding_receipt_date->toDateString() }}</td>
                        <td>{{ $withheld->agent_name }}</td>
                        <td>{{ $withheld->agent_no }}</td>
                        <td>{{ $withheld->vfms_receipt_no }}</td>
                        <td>{{ $withheld->vfms_receipt_date->toDateString() }}</td>
                        <td>{{ number_format($withheld->net_amount) }} {{ $withheld->currency }}</td>
                        <td>{{ number_format($withheld->tax_withheld) }} {{ $withheld->currency }}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        <a class="file-item d-inline-flex pr-3 mr-2" target="_blank"
           href="{{ route('returns.stamp-duty.withheld-certificates-summary', encrypt($return->id)) }}">
            <i class="bi bi-file-earmark-excel px-2 font-x-large"></i>
            <div class="ml-1 font-weight-bold">
                Withheld Certificates Summary
            </div>
            <i class="bi bi-arrow-up-right-square ml-2"></i>
        </a>

        @if(!empty($return->withheldCertificates))
            @foreach($return->withheldCertificates as $certificate)
                <a class="file-item d-inline-flex pr-3 mr-2" target="_blank"
                   href="{{ route('returns.stamp-duty.withheld-certificate', encrypt($certificate->id)) }}">
                    <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                    <div class="ml-1 font-weight-bold">
                        Withheld Certificate {{ $loop->index + 1 }}
                    </div>
                    <i class="bi bi-arrow-up-right-square ml-2"></i>
                </a>
            @endforeach
        @endif
    @endif
</div>