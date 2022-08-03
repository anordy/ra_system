@extends('layouts.master')

@section('title')
    Vat Returns
@endsection
@section('stylesheet')
    <style>
        .tab-content {
            padding: 10px;
            background: #fff;
            box-shadow: rgb(0 0 0 / 16%) 0px 1px 4px;
        }
    </style>
@endsection

@section('content')
    <div>
        <div class="d-flex justify-content-end mb-3">
            <a href="{{route('returns.index')}}" class="btn btn-info">Back</a>
        </div>

        <div class="card">
            <div class="card-body">

                <div class="card">
                    <div class="card-header">
                        Business Details
                    </div>
                    <div class="card-body">
                        <div class="row m-2 pt-3">
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Tax Type</span>
                                <p class="my-1">{{ $return->taxtype->name }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Tax Payer Name</span>
                                <p class="my-1">{{ $return->taxtype->name }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Business Name</span>
                                <p class="my-1">{{ $return->business->name }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Business Location</span>
                                <p class="my-1">{{ $return->branch->name ?? 'Head Quarter' }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">TIN No</span>
                                <p class="my-1">{{ $return->business->tin }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Mobile</span>
                                <p class="my-1">{{ $return->business->mobile }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Email</span>
                                <p class="my-1">{{ $return->business->email }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Filled By</span>
                                <p class="my-1">{{ $return->business->taxpayer->first_name.' ' .$return->business->taxpayer->last_name}}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Financial Year</span>
                                <p class="my-1">{{ $return->financialYear->name }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Return Month</span>
                                <p class="my-1">July</p>
                            </div>

                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Currency</span>
                                <p class="my-1">{{ $return->business->currency->iso ?? 'Head Quarter' }}</p>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Items</div>
                    <div class="card-body">
                        <table class="table table-bordered ">
                            <thead>
                            <th style="width: 30%">Item Name</th>
                            <th style="width: 20%">Value</th>
                            <th style="width: 10%">Rate</th>
                            <th class="text-right" style="width: 20%">VAT</th>
                            </thead>
                            <tbody>
                            @foreach ($return->items as $item)
                                <tr>
                                    <td>{{ $item->config->name }}</td>
                                    <td>{{ number_format($item->input_amount) }}</td>
                                    <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }}
                                        @if($item->config->rate_type =='percentage')
                                            %
                                        @endif
                                    </td>
                                    <td class="text-right">{{ number_format($item->vat_amount,2) }} <strong>{{$return->business->currency->iso}}</strong></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Calculation</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-right">Amount</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr>
                                <td>Output Tax</td>
                                <td class="text-right">{{number_format($return->total_output_tax,2, '.',',')}}
                                    <strong>{{$return->business->currency->iso}}</strong>
                                </td>
                            </tr>

                            <tr>
                                <td>Input Tax</td>
                                <td class="text-right">{{number_format($return->total_input_tax,2, '.',',')}}
                                    <strong>{{$return->business->currency->iso}}</strong>
                                </td>
                            </tr>

                            <tr>
                                <th>Vat Payable/To Claim</th>
                                <th class="text-right">{{number_format($return->total_vat_payable,2, '.',',')}}
                                    <strong>{{$return->business->currency->iso}}</strong>
                                </th>
                            </tr>

                            <tr>
                                <td>Vat Withheld</td>
                                <td class="text-right">{{number_format($return->vat_withheld,2, '.',',')}}
                                    <strong>{{$return->business->currency->iso}}</strong>
                                </td>
                            </tr>

                            <tr>
                                <td>Vat Credit Brought Forward</td>
                                <td class="text-right">{{number_format($return->vat_credit_brought_forward,2, '.',',' )}}
                                    <strong>{{$return->business->currency->iso}}</strong>
                                </td>
                            </tr>

                            <tr>
                                <th>Infrastructure Vat To Be Paid ({{$return->business->business_type}})</th>
                                <th class="text-right">{{number_format($return->infrastructure_tax,2, '.',',' )}}
                                    <strong>{{$return->business->currency->iso}}</strong>
                                </th>
                            </tr>

                            <tr>
                                <th>Net Vat Payable</th>
                                <th class="text-right">{{number_format($return->total_vat_amount_due, 2, '.',',')}}
                                    <strong>{{$return->business->currency->iso}}</strong>
                                </th>
                            </tr>

                            {{--                        <tr>--}}
                            {{--                            <th>Penalty for late filling</th>--}}
                            {{--                            <th class="text-right">{{number_format($filling_penalty, 2, '.',',')}}--}}
                            {{--                                <strong>{{$return->business->currency->iso}}</strong>--}}
                            {{--                            </th>--}}
                            {{--                        </tr>--}}

                            {{--                        <tr>--}}
                            {{--                            <th>Total Vat Amount Due</th>--}}
                            {{--                            <th class="text-right">{{number_format($return->total_vat_amount_due, 2, '.',',')}}--}}
                            {{--                                <strong>{{$return->business->currency->iso}}</strong>--}}
                            {{--                            </th>--}}
                            {{--                        </tr>--}}


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
@endsection
