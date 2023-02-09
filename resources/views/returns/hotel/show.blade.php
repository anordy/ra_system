@extends('layouts.master')

@section('title', 'View Hotel Tax Returns')

@section('content')
    <div class="row mx-1">
        <div class="col-md-12">
            <livewire:returns.return-payment :return="$return->tax_return" />
        </div>
    </div>
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            {{ $return->taxtype->name }} Tax Returns Details for
            {{ $return->financialMonth->name }},
            {{ $return->financialMonth->year->code }}
        </div>
        <div class="card-body">
            <h6 class="text-uppercase mt-2 ml-2"></h6>
            <div>
                <ul style="border-bottom: unset !important;" class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#biz" role="tab"
                            aria-controls="home" aria-selected="true">Business Details</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="academic-tab" data-toggle="tab" href="#academic" role="tab"
                            aria-controls="profile" aria-selected="false">Return Items</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="penalty-tab" data-toggle="tab" href="#penalty" role="tab"
                            aria-controls="penalty" aria-selected="false">Penalties</a>
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

                        <x-bill-structure :bill="$return->tax_return->latestBill" :withCard="false"/>
                    </div>
                    <div class="tab-pane p-2" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped normal-text">
                                    <thead>
                                        <th style="width: 30%">Item Name</th>
                                        <th style="width: 20%">Value</th>
                                        <th style="width: 10%">Rate</th>
                                        <th style="width: 20%">Tax</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($return->items as $item)
                                            <tr>
                                                <td>{{ $item->config->name }}</td>
                                                <td>{{ number_format($item->value, 2) }}</td>
                                                <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }}
                                                </td>
                                                <td>{{ number_format($item->vat, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane p-2" id="penalty" role="tabpanel" aria-labelledby="penalty-tab">

                        <div class="col-md-12 pt-3">
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
                                                <td>{{ number_format($penalty['rate_percentage'], 4) }}
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
