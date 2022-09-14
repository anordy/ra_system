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
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Tax Type</span>
                                <p class="my-1">{{ $return->taxtype->name }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Filled By</span>
                                <p class="my-1">{{ $return->taxpayer->full_name }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Financial Year</span>
                                <p class="my-1">{{ $return->financialYear->name }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Business Name</span>
                                <p class="my-1">{{ $return->business->name }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Business Location</span>
                                <p class="my-1">{{ $return->branch->name ?? 'Head Quarter' }}</p>
                            </div>
                        </div>

                        <x-bill-structure :bill="$return->tax_return->latestBill()" :withCard="false"/>
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
                                                <td>{{ number_format($item->value) }}</td>
                                                <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }}
                                                </td>
                                                <td>{{ number_format($item->vat) }}</td>
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

        </div>
    </div>


@endsection
