<table style="border-collapse:collapse; width:100%">
    <thead>
    <tr>
        <th>
            <p style="margin-bottom: 0px; margin-top: 25px; text-align: left; font-size: 18px; text-transform: uppercase">Return Details</p>
        </th>
    </tr>
    </thead>
</table>
<table class="tbl-bordered tbl-p-6" style="width: 100%; margin-top: 10px;">
    <thead>
    <th style="width: 30%">Item Name</th>
    <th style="width: 20%">Value ({{ $return->currency }})</th>
    <th style="width: 10%">Rate</th>
    <th style="width: 20%">Tax ({{ $return->currency  }})</th>
    </thead>
    <tbody>
    @foreach ($return->items as $item)

        @if ($item->config->rate == 0 && $item->config->col_type != 'exemptedMethodOne')
            <tr>
                <td>{{ $item->config->name }}</td>
                <td class="text-right">{{ number_format($item->value, 2) }}
                    <strong>  {{ $item->config->currency}}</strong></td>
                <td class="table-active"></td>
                <td class="text-right">{{ number_format($item->vat,2) }}
                    <strong>{{$return->currency}}</strong></td>
            </tr>
        @else
            @if($item->config->code == 'ITH')
                @if($return->business->business_type =='hotel')
                    <tr>
                        <td>{{ $item->config->name }}</td>
                        <td class="text-right">{{ number_format($item->value, 2) }} <strong>(No.
                                of bed nights)</strong></td>
                        <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate . '%' : $item->config->rate }}
                            @if($item->config->rate_type =='percentage')
                                %
                            @else
                                {{$item->config->currency}}
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($return->infrastructure_tax,2) }}
                            <strong>{{$return->currency}}</strong></td>
                    </tr>
                @endif
            @elseif($item->config->code == 'ITE')
                @if($return->business->business_type =='electricity')
                    <tr>
                        <td>{{ $item->config->name }}</td>
                        <td class="text-right">{{ number_format($item->value, 2) }}
                            <strong>(Electricity Units)</strong></td>
                        {{ $item->config->rate_type === 'percentage' ? $item->config->rate . '%' : $item->config->rate_usd .''. $item->config->currency }}
                        <td>
                        </td>
                        <td class="text-right">{{ number_format($return->infrastructure_tax,2) }}
                            <strong>{{$return->currency}}</strong></td>
                    </tr>
                @endif

            @elseif($item->config->code != 'TIT' && $item->config->code != 'TITM1')
                <tr>
                    <td>{{ $item->config->name }}</td>
                    <td class="text-right">{{ number_format($item->value, 2) }}
                        <strong>  {{ $item->config->currency}}</strong></td>
                    <td> {{ $item->config->rate_type === 'percentage' ? $item->config->rate . '%' : $item->config->rate_usd .''. $item->config->currency }}
                    </td>
                    <td class="text-right">{{ number_format($item->vat,2) }}
                        <strong>{{$return->currency}}</strong></td>
                </tr>
            @endif
        @endif


    @endforeach
    <tr>
        <td>Total Output Tax</td>
        <td colspan="2" class="table-active"></td>
        <td class="text-right">{{number_format($return->total_output_tax,2, '.',',')}}
            <strong>{{$return->currency}}</strong>
        </td>
    </tr>

    <tr>
        <td>Total Input Tax</td>
        <td colspan="2" class="table-active"></td>
        <td class="text-right">{{number_format($return->total_input_tax,2, '.',',')}}
            <strong>{{$return->currency}}</strong>
        </td>
    </tr>

    <tr>
        <th>
            @if($return->claim_status == \App\Enum\TaxClaimStatus::CLAIM)
                Vat To Claim
            @else
                Vat Payable
            @endif
        </th>
        <td colspan="2" class="table-active"></td>
        <th class="text-right">
            @if($return->claim_status == \App\Enum\TaxClaimStatus::CLAIM)
                ({{number_format($return->total_vat_payable, 2, '.',',')}})
            @else
                {{number_format($return->total_vat_payable, 2, '.',',')}}
            @endif
            <strong>{{$return->currency}}</strong>
        </th>
    </tr>

    <tr>
        <td>Vat Withheld</td>
        <td colspan="2" class="table-active"></td>
        <td class="text-right">{{number_format($return->vat_withheld,2, '.',',')}}
            <strong>{{$return->currency}}</strong>
        </td>
    </tr>

    <tr>
        <td>Vat Credit Brought Forward</td>
        <td colspan="2" class="table-active"></td>
        <td class="text-right">{{number_format($return->vat_credit_brought_forward,2, '.',',' )}}
            <strong>{{$return->currency}}</strong>
        </td>
    </tr>

    <tr>
        <th>
            @if($return->claim_status == \App\Enum\TaxClaimStatus::CLAIM)
                Net Vat To Claim
            @else
                Net Vat Payable
            @endif
        </th>
        <td colspan="2" class="table-active"></td>
        <th class="text-right">
            @if($return->claim_status == \App\Enum\TaxClaimStatus::CLAIM)
                ({{number_format($return->total_amount_due, 2, '.',',')}})
            @else
                {{number_format($return->total_amount_due, 2, '.',',')}}
            @endif
            <strong>{{$return->currency}}</strong>
        </th>
    </tr>

    <tr>
        @if($return->business_type != 'other')
            <th>Infrastructure Vat To Be Paid ({{$return->business_type}})</th>
            <td colspan="2" class="table-active"></td>
            <th class="text-right">{{number_format($return->infrastructure_tax,2, '.',',' )}}
                <strong>{{$return->currency}}</strong>
            </th>
        @endif
    </tr>

    <tr>
        <th>Penalty</th>
        <td colspan="2" class="table-active"></td>
        <th class="text-right">{{number_format($return->penalty,2, '.',',' )}}
            <strong>{{$return->currency}}</strong>
        </th>
    </tr>

    <tr>
        <th>Interest</th>
        <td colspan="2" class="table-active"></td>
        <th class="text-right">{{number_format($return->interest,2, '.',',' )}}
            <strong>{{$return->currency}}</strong>
        </th>
    </tr>
    </tbody>
    <tfoot>
    <tr class="bg-secondary">
        <th style="width: 20%">Total</th>
        <th style="width: 30%"></th>
        <th style="width: 25%"></th>
        <th style="width: 25%">
            {{ number_format($return->total_amount_due_with_penalties, 2) }}
            <strong>{{$return->currency}}</strong></th>
    </tr>
    </tfoot>
</table>