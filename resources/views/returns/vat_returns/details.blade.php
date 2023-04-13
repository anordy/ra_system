<div class="card">
    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Filled Return Details</h6>
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
                    @foreach ($return->items as $item)

                        @if($item->config->code == 'ITH')
                            @if($return->business->business_type =='hotel')
                                <tr>
                                    <td>{{ $item->config->name }}</td>
                                    <td class="text-right">{{ number_format($item->value, 2) }} <strong>(No.
                                            of bed nights)</strong></td>
                                    <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate }}
                                        @if($item->config->rate_type =='percentage')
                                            %
                                        @else
                                            {{$item->config->currency}}
                                        @endif
                                    </td>
                                    <td class="text-right">{{ number_format($return->infrastructure_tax,2) }}
{{--                                        <strong>{{$return->business->currency->iso}}</strong></td>--}}
                                </tr>
                            @endif
                        @elseif($item->config->code == 'ITE')
                            @if($return->business->business_type =='electricity')
                                <tr>
                                    <td>{{ $item->config->name }}</td>
                                    <td class="text-right">{{ number_format($item->value, 2) }}
                                        <strong>{{$item->config->currency}}</strong></td>
                                    <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate }}
                                        @if($item->config->rate_type =='percentage')
                                            %
                                        @else
                                            {{$item->config->currency}}
                                        @endif
                                    </td>
                                    <td class="text-right">{{ number_format($return->infrastructure_tax,2) }}
{{--                                        <strong>{{$return->business->currency->iso}}</strong></td>--}}
                                </tr>
                            @endif

                        @elseif($item->config->code != 'TIT' && $item->config->code != 'TITM1')
                            <tr>
                                <td>{{ $item->config->name }}</td>
                                <td class="text-right">{{ number_format($item->value, 2) }}
                                    <strong>  {{ $item->config->currency}}</strong></td>
                                <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }}
                                    @if($item->config->rate_type =='percentage')
                                        %
                                    @endif
                                </td>
                                <td class="text-right">{{ number_format($item->vat,2) }}
{{--                                    <strong>{{$return->business->currency->iso}}</strong></td>--}}
                            </tr>
                        @endif


                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="3" style="width: 20%">Vat Withheld</th>
                        <th class="text-right" style="width: 25%">{{ number_format($return->vat_withheld,2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="3" style="width: 20%">Vat Credit Brought Forward</th>
                        <th class="text-right" style="width: 25%">{{ number_format($return->vat_credit_brought_forward,2) }}</th>
                    </tr>

                    <tr>
                        <th colspan="3" style="width: 20%">Total Vat Payable</th>
                        <th class="text-right" style="width: 25%">{{ number_format($return->total_amount_due,2) }}</th>
                    </tr>

{{--                    <tr>--}}
{{--                        <th>Infrastructure Vat To Be Paid ({{$return->infrastructure_tax}})</th>--}}
{{--                        <th class="text-right">{{number_format($return->infrastructure_tax,2, '.',',' )}}--}}
{{--                            <strong>{{$return->business->currency->iso}}</strong>--}}
{{--                        </th>--}}
{{--                    </tr>--}}

                    <tr>
                        <th colspan="3" style="width: 20%">Penalty</th>
                        <th class="text-right" style="width: 25%">{{ number_format($return->penalty,2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="3" style="width: 20%">Grant Vat Payable</th>
                        <th class="text-right" style="width: 25%">{{ number_format($return->total_amount_due_with_penalties,2) }}</th>
                    </tr>

                    </tfoot>
                </table>

            </div>
        </div>
    </div>
</div>
