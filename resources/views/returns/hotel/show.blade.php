@extends('layouts.master')

@section('title', 'View Hotel Returns')

@section('content')
    <div class="card">
        <div class="card-body">
            <h6 class="text-uppercase mt-2 ml-2">{{ $return->taxtype->name }} Returns Details for
                {{ $return->financialMonth->name }},
                {{ $return->financialMonth->year->code }}</h6>
            <hr>

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
                            {{-- <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Payment Status</span>
                                <p class="my-1">
                                    @if ($return->status === 'completed')
                                        <span class="badge badge-success py-1 px-2"
                                            style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                                            Paid
                                        </span>
                                    @else
                                        <span class="badge badge-success py-1 px-2"
                                            style="border-radius: 1rem; background: #dc354559; color: #cf1c2d;; font-size: 85%">
                                            Not Paid
                                        </span>
                                    @endif
                                </p>
                            </div> --}}
                        </div>

                        <div class="row m-2 pt-3">
                            <h6>Payment Summary</h6>

                            <table class="table table-sm">
                                <thead>
                                    <th style="width: 20%">Items</th>
                                    <th style="width: 30%">Amount</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>{{ $return->taxtype->name }} </th>
                                        <td>{{ number_format($return->total_amount_due, 2) }}</td>
                                    </tr>
                                    @if ($return->hotel_infrastructure_tax > 0)
                                        <tr>
                                            <th>Infrastructure Tax</th>
                                            <td>{{ number_format($return->hotel_infrastructure_tax, 2) }}</td>
                                        </tr>
                                    @endif

                                    @if ($return->interest > 0)
                                        <tr>
                                            <th>Interest Amount</th>
                                            <td>{{ number_format($return->interest, 2) }}</td>
                                        </tr>
                                    @endif

                                    @if ($return->penalty > 0)
                                        <tr>
                                            <th>Penalty Amount</th>
                                            <td>{{ number_format($return->penalty, 2) }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>TOTAL PAYABLE</th>
                                        <th>{{ number_format($return->total_amount_due + $return->hotel_infrastructure_tax + $return->interest + $return->penalty, 2) }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane p-2" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <p class="text-uppercase font-weight-bold">Return Items</p>
                            </div>
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
