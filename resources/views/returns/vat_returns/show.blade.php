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
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Return details for the VAT tax return for the return month
            of {{$return->financialMonth->name}} {{$return->financialMonth->year->code}}
        </div>
        <div class="card-body">
            <div style="margin-left: 13px; margin-right: 15px;">
                <livewire:returns.return-payment :return="$return->tax_return" />
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
                               aria-controls="profile" aria-selected="false">Return Details</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="prof-tab" data-toggle="tab" href="#prof" role="tab"
                               aria-controls="contact"
                               aria-selected="false">Supplier Details</a>
                        </li>


                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="training-tab" data-toggle="tab" href="#training" role="tab"
                               aria-controls="contact" aria-selected="false">Penalties</a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="payment-summary-tab" data-toggle="tab" href="#payment-summary" role="tab"
                               aria-controls="payment-summary" aria-selected="false">Payment Summary</a>
                        </li>
                    </ul>
                    <div style="border: 1px solid #eaeaea;" class="tab-content" id="myTabContent">

                        <div class="tab-pane p-2 show active" id="biz" role="tabpanel" aria-labelledby="biz-tab">
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
                                    <p class="my-1">{{ $return->businessLocation->name ?? 'Head Quarter' }}</p>
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
                                    <p class="my-1">{{ $return->taxpayer->first_name.' ' .$return->taxpayer->middle_name.' ' .$return->taxpayer->last_name}}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">Return Month</span>
                                    <p class="my-1">{{$return->financialMonth->name}} {{ $return->financialYear->code }}</p>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">Currency</span>
                                    <p class="my-1">{{ $return->currency ?? 'Head Quarter' }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">Return Application Status</span>
                                    <p class="my-1">
                                        @if($return->application_status == \App\Enum\ReturnApplicationStatus::SUBMITTED)
                                            <span class="badge badge-success py-1 px-2"
                                                  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 100%"><i
                                                        class="bi bi-check-circle-fill mr-1"></i>
                                                Submitted
                                            </span>

                                        @elseif($return->application_status == \App\Enum\ReturnApplicationStatus::ADJUSTED)
                                            <span class="badge badge-danger py-1 px-2"
                                                  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 100%"><i
                                                        class="bi bi-check-circle-fill mr-1"></i>
                                                Adjusted
                                            </span>
                                        @elseif($return->application_status == \App\Enum\ReturnApplicationStatus::SELF_ASSESSMENT)
                                            <span class="badge badge-success py-1 px-2"
                                                  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 100%"><i
                                                        class="bi bi-check-circle-fill mr-1"></i>
                                                self Assessment
                                            </span>
                                        @endif
                                    </p>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane p-2" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                            <div class="card">
                                <div class="card-body">
                                        <div class="d-flex justify-content-end">
                                            <div class="pb-2" style="width: 160px">
                                                <label>Exemption Method Used</label>
                                                <input readonly class="form-control" type="text" value="{{ $return->method_used ?? 'No Method Used' }}">
                                            </div>

                                        </div>
                                    <table class="table table-bordered ">
                                        <thead>
                                        <th style="width: 40%">Item Name</th>
                                        <th style="width: 20%">Value</th>
                                        <th style="width: 20%">Rate</th>
                                        <th class="text-right" style="width: 20%">VAT</th>
                                        </thead>
                                        <tbody>
                                        @foreach ($return->items as $item)
                                            @if($item->config->code == 'ITH')
                                                @if($return->business->business_type =='hotel')
                                                    <tr>
                                                        <td>{{ $item->config->name }}</td>
                                                        <td class="text-right">{{ number_format($item->value) }} <strong>(No.
                                                                of bed nights)</strong></td>
                                                        <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate }}
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
                                                        <td class="text-right">{{ number_format($item->value) }}
                                                            <strong>(Electricity Units)</strong></td>
                                                        <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate }}
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

                                            @elseif($item->config->code != 'TIT' && $item->config->code != 'TITM1')
                                                <tr>
                                                    <td>{{ $item->config->name }}</td>
                                                    <td class="text-right">{{ number_format($item->value) }}
                                                        <strong>  {{ $item->config->currency}}</strong></td>
                                                    <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }}
                                                        @if($item->config->rate_type =='percentage')
                                                            %
                                                        @endif
                                                    </td>
                                                    <td class="text-right">{{ number_format($item->vat,2) }}
                                                        <strong>{{$return->currency}}</strong></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>

                                        <tbody>

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
                                                    ({{number_format(abs($return->total_output_tax - $return->total_input_tax), 2, '.',',')}})
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

                                        <tr>
                                            <th>Grant Vat Payable</th>
                                            <td colspan="2" class="table-active"></td>
                                            <th class="text-right">{{number_format($return->total_amount_due_with_penalties, 2, '.',',')}}
                                                <strong>{{$return->currency}}</strong>
                                            </th>
                                        </tr>

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane p-2" id="prof" role="tabpanel" aria-labelledby="prof-tab">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered table-sm normal-text">
                                        <thead>
                                        <tr>
                                            <th>No:</th>
                                            <th>Taxpayer Zin Number</th>
                                            <th>Supplier Zin Number</th>
                                            <th>Tax Invoice Number</th>
                                            <th>Date Of Invoice</th>
                                            <th>Release Number</th>
                                            <th>Value</th>
                                            <th>Vat</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @if (count($return->suppliers))
                                            @foreach ($return->suppliers as $index=> $details)
                                                <tr>
                                                    <th>{{$index + 1}}</th>
                                                    <td>{{ $details['taxpayer_zin_number'] }}</td>
                                                    <td>{{ $details['supplier_zin_number'] }}

                                                    <td>{{ $details['tax_invoice_number'] }}

                                                    <td>{{ date('D, Y-m-d', strtotime($details['date_of_tax_invoice'])) }}

                                                    <td>{{ $details['release_number'] }}

                                                    </td>
                                                    <td class="text-right">{{ $details['value'] }}

                                                    <td class="text-right">{{ $details['vat']}}

                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center py-3">
                                                    No details for supplier for this return month.
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane p-2" id="training" role="tabpanel" aria-labelledby="training-tab">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered table-sm normal-text">
                                        <thead>
                                        <tr>
                                            <th>NO:</th>
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
                                            @foreach ($return->penalties as $index=> $penalty)
                                                <tr>
                                                    <th>{{$index + 1}}</th>
                                                    <td>{{ $penalty['financial_month_name'] }}</td>
                                                    <td>{{ number_format($penalty['tax_amount'], 2) }}
                                                        <strong>{{ $return->currency }}</strong></td>
                                                    <td>{{ number_format($penalty['late_filing'], 2) }}
                                                        <strong>{{ $return->currency }}</strong></td>
                                                    <td>{{ number_format($penalty['late_payment'], 2) }}
                                                        <strong>{{ $return->currency }}</strong></td>
                                                    <td>{{ number_format($penalty['rate_percentage'], 2) }}
                                                        <strong>%</strong>
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
                        <div class="tab-pane p-2" id="payment-summary" role="tabpanel" aria-labelledby="payment-summary-tab">
                            <x-bill-structure :bill="$return->tax_return->latestBill" :withCard="false"/>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-danger text-center">
                    you have not filled any return for this month
                </div>
            @endif

            <div class="row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <a href="{{ route('returns.print', encrypt($return->tax_return->id)) }}" target="_blank" class="btn btn-info">
                        <i class="bi bi-printer-fill mr-2"></i>
                        Print Return
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
