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
        {{-- <div class="d-flex justify-content-end mb-3">
            <a href="{{route('vat-return.index')}}" class="btn btn-info">Back</a>
        </div> --}}

        <div>
            <div class="card">
                <div class="card-header">
                    Return details for the return month of {{$return->financialMonth->name}}
                </div>
                <div class="card-body">
                    <div>
                        <livewire:returns.return-payment :return="$return"/>
                    </div>
                    @if(!empty($return))
                        <div>
                            <ul style="border-bottom: unset !important;" class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#biz" role="tab"
                                       aria-controls="home"
                                       aria-selected="true">Business Details</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="academic-tab" data-toggle="tab" href="#academic" role="tab"
                                       aria-controls="profile" aria-selected="false">Return Items</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="prof-tab" data-toggle="tab" href="#prof" role="tab"
                                       aria-controls="contact"
                                       aria-selected="false">Calculations</a>
                                </li>


                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="training-tab" data-toggle="tab" href="#training" role="tab"
                                       aria-controls="contact" aria-selected="false">Penalties</a>
                                </li>
                            </ul>
                            <div style="border: 1px solid #eaeaea;" class="tab-content" id="myTabContent">

                                <div class="tab-pane p-2 show active" id="biz" role="tabpanel"
                                     aria-labelledby="biz-tab">
                                    <div class="row m-2 pt-3">
                                        <div class="col-md-3 mb-3">
                                            <span class="font-weight-bold text-uppercase">Tax Type</span>
                                            <p class="my-1">{{ $return->taxtype->name }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <span class="font-weight-bold text-uppercase">Tax Payer Name</span>
                                            <p class="my-1">{{ $return->business->taxpayer->first_name.' '. $return->business->taxpayer->middle_name.' '.$return->business->taxpayer->last_name }}</p>
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
                                            <span class="font-weight-bold text-uppercase">Business Type</span>
                                            <p class="my-1">{{ $return->business->business_type }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <span class="font-weight-bold text-uppercase">Filled By</span>
                                            <p class="my-1">{{ $return->business->taxpayer->first_name.' ' .$return->business->taxpayer->middle_name.' ' .$return->business->taxpayer->last_name}}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <span class="font-weight-bold text-uppercase">Financial Year</span>
                                            <p class="my-1">{{ $return->financialYear->name }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <span class="font-weight-bold text-uppercase">Return Month</span>
                                            <p class="my-1">{{$return->financialMonth->name}}</p>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <span class="font-weight-bold text-uppercase">Currency</span>
                                            <p class="my-1">{{ $return->business->currency->iso ?? 'Head Quarter' }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <span class="font-weight-bold text-uppercase">Status</span>
                                            <p class="my-1">
                                                @if($return->status == 'complete')
                                                    <span class="badge badge-success py-1 px-2"
                                                          style="border-radius: 1rem; background: #35dcb5; color: #0a9e99; font-size: 85%">
        <i class="bi bi bi-x-circle-fill mr-1"></i>
        {{\App\Models\Returns\ReturnStatus::COMPLETE}}
    </span>

                                                @elseif($return->status == 'submitted')
                                                    <span class="badge badge-success py-1 px-2"
                                                          style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{\App\Models\Returns\ReturnStatus::SUBMITTED}}
    </span>
                                                @elseif($return->status == 'control-number-generating')
                                                    <span class="badge badge-success py-1 px-2"
                                                          style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{\App\Models\Returns\ReturnStatus::CN_GENERATING}}
    </span>
                                                @elseif($return->status == 'control-number-generated')
                                                    <span class="badge badge-success py-1 px-2"
                                                          style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{\App\Models\Returns\ReturnStatus::CN_GENERATED}}
    </span>
                                                @elseif($return->status == 'control-number-generating-failed')
                                                    <span class="badge badge-success py-1 px-2"
                                                          style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{\App\Models\Returns\ReturnStatus::CN_GENERATION_FAILED}}
    </span>
                                                @else
                                                    <span class="badge badge-success py-1 px-2"
                                                          style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{\App\Models\Returns\ReturnStatus::PAID_PARTIALLY}}
    </span>
                                                @endif
                                            </p>
                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane p-2" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                                    <table class="table table-bordered ">
                                        <thead>
                                        <th style="width: 30%">Item Name</th>
                                        <th style="width: 20%">Value</th>
                                        <th style="width: 10%">Rate</th>
                                        <th class="text-right" style="width: 20%">VAT</th>
                                        </thead>
                                        <tbody>
                                        @foreach ($return->items as $item)

                                            @if($item->config->code == 'ITH')
                                                @if($return->business->business_type =='hotel')
                                                    <tr>
                                                        <td>{{ $item->config->name }}</td>
                                                        <td class="text-right">{{ number_format($item->input_amount) }}
                                                            <strong>(No.
                                                                of bed nights)</strong></td>
                                                        <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate }}
                                                            @if($item->config->rate_type =='percentage')
                                                                %
                                                            @else
                                                                {{$item->config->currency}}
                                                            @endif
                                                        </td>
                                                        <td class="text-right">{{ number_format($return->infrastructure_tax,2) }}
                                                            <strong>{{$return->business->currency->iso}}</strong></td>
                                                    </tr>
                                                @endif
                                            @elseif($item->config->code == 'ITE')
                                                @if($return->business->business_type =='electricity')
                                                    <tr>
                                                        <td>{{ $item->config->name }}</td>
                                                        <td class="text-right">{{ number_format($item->input_amount) }}
                                                            <strong>{{$item->config->currency}}</strong></td>
                                                        <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate }}
                                                            @if($item->config->rate_type =='percentage')
                                                                %
                                                            @else
                                                                {{$item->config->currency}}
                                                            @endif
                                                        </td>
                                                        <td class="text-right">{{ number_format($return->infrastructure_tax,2) }}
                                                            <strong>{{$return->business->currency->iso}}</strong></td>
                                                    </tr>
                                                @endif

                                            @elseif($item->config->code != 'TIT' && $item->config->code != 'TITM1')
                                                <tr>
                                                    <td>{{ $item->config->name }}</td>
                                                    <td class="text-right">{{ number_format($item->input_amount) }}
                                                        <strong>  {{ $item->config->currency}}</strong></td>
                                                    <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }}
                                                        @if($item->config->rate_type =='percentage')
                                                            %
                                                        @endif
                                                    </td>
                                                    <td class="text-right">{{ number_format($item->vat_amount,2) }}
                                                        <strong>{{$return->business->currency->iso}}</strong></td>
                                                </tr>
                                            @endif


                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane p-2" id="prof" role="tabpanel" aria-labelledby="prof-tab">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th class="text-right">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <tr>
                                            <td>Total Output Tax</td>
                                            <td class="text-right">{{number_format($return->total_output_tax,2, '.',',')}}
                                                <strong>{{$return->business->currency->iso}}</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Total Input Tax</td>
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
                                            <th>Net Vat Payable</th>
                                            <th class="text-right">{{number_format($return->total_amount_due, 2, '.',',')}}
                                                <strong>{{$return->business->currency->iso}}</strong>
                                            </th>
                                        </tr>

                                        <tr>
                                            <th>Infrastructure Vat To Be Paid ({{$return->business_type}})</th>
                                            <th class="text-right">{{number_format($return->infrastructure_tax,2, '.',',' )}}
                                                <strong>{{$return->business->currency->iso}}</strong>
                                            </th>
                                        </tr>

                                        <tr>
                                            <th>Penalty</th>
                                            <th class="text-right">{{number_format($return->penalty,2, '.',',' )}}
                                                <strong>{{$return->business->currency->iso}}</strong>
                                            </th>
                                        </tr>

                                        <tr>
                                            <th>Interest</th>
                                            <th class="text-right">{{number_format($return->interest,2, '.',',' )}}
                                                <strong>{{$return->business->currency->iso}}</strong>
                                            </th>
                                        </tr>

                                        <tr>
                                            <th>Grant Vat Payable</th>
                                            <th class="text-right">{{number_format($return->total_amount_due_with_penalties, 2, '.',',')}}
                                                <strong>{{$return->business->currency->iso}}</strong>
                                            </th>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane p-2" id="training" role="tabpanel" aria-labelledby="training-tab">
                                    <div class="col-md-12">

                                        <table class="table table-bordered table-sm normal-text">
                                            <thead>
                                            <tr>
                                                <th>Month</th>
                                                <th>Tax Amount</th>
                                                <th>Late Filing Amount</th>
                                                <th>Late Payment Amount</th>
                                                <th>Interest Rate</th>
                                                <th>Interest Amount</th>
                                                <th>Penalty Amount</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @if (count($return->penalties))
                                                @foreach ($return->penalties as $penalty)
                                                    <tr>
                                                        <td>{{ $penalty['financial_month_name'] }}</td>
                                                        <td>{{ number_format($penalty['tax_amount'], 2) }}
                                                            <strong>{{ $return->currency }}</strong></td>
                                                        <td>{{ number_format($penalty['late_filing'], 2) }}
                                                            <strong>{{ $return->currency }}</strong></td>
                                                        <td>{{ number_format($penalty['late_payment'], 2) }}
                                                            <strong>{{ $return->currency }}</strong></td>
                                                        <td>{{ number_format($penalty['rate_percentage'], 2) }} <strong>%</strong>
                                                        </td>
                                                        <td>{{ number_format($penalty['rate_amount'], 2) }}
                                                            <strong>{{ $return->currency }}</strong></td>
                                                        <td>{{ number_format($penalty['penalty_amount'], 2) }}
                                                            <strong>{{ $return->currency }}</strong></td>
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

                    @else
                        <div class="alert alert-danger text-center">
                            you have not filled any return for this month
                        </div>
                    @endif
                </div>
            </div>
        </div>


@endsection
