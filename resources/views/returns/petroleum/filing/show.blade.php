@extends('layouts.master')

@section('title', 'View Petroleum Returns')

@section('content')
    @if(!empty($return->tax_return))
        <div class="row mx-2 pt-3">
            <div class="col-md-12">
                <livewire:returns.return-payment :return="$return->tax_return"/>
            </div>
        </div>
    @endif

    <div class="card rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Petroleum Tax Return
        </div>
        <div class="card-body">
            <ul  class="nav nav-tabs" id="myTab">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#summary" role="tab"
                       aria-controls="home" aria-selected="true">Summary</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="return-items-tab" data-toggle="tab" href="#return-items" role="tab"
                       aria-controls="profile" aria-selected="false">Return Information</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="penalties-tab" data-toggle="tab" href="#penalties" role="tab"
                       aria-controls="penalties" aria-selected="false">Penalties</a>
                </li>
            </ul>
            <div class="tab-content border border-white" id="myTabContent">

                <div class="tab-pane p-2 show active" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                    <div class="row m-2 pt-3">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Tax Type</span>
                            <p class="my-1">{{ $return->taxtype->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Filed By</span>
                            <p class="my-1">{{ $return->taxpayer->full_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Financial Year</span>
                            <p class="my-1">{{ $return->financialYear->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Name</span>
                            <p class="my-1">{{ $return->business->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Location</span>
                            <p class="my-1">{{ $return->branch->name ?? 'Head Quarter' }}</p>
                        </div>
                    </div>

                    <x-bill-structure :bill="$return->tax_return->latestBill" :withCard="false"/>
                </div>

                <div class="tab-pane p-2" id="return-items" role="tabpanel" aria-labelledby="return-items-tab">
                    <table class="table table-bordered table-responsive table-sm table">
                        <thead>
                        <th>Item Name</th>
                        <th>Number of Litres/ Value</th>
                        <th>Rate per Litre</th>
                        <th>Amount ({{$return->currency}})</th>
                        </thead>
                        <tbody>
                        @if(!empty($return->configReturns))
                            @foreach ($return->configReturns as $item)
                                @if($item->config->col_type == 'heading')
                                @elseif($item->config->code === 'MSP' && $item->config->rate == 300 && $item->vat == 0)
                                @else
                                <tr>
                                    <td>{{ $item->config->name ?? 'name' }}</td>
                                    <td>{{ number_format($item->value, 2) }}</td>
                                    <td>
                                        @if($item->config->rate_type === 'percentage')
                                            {{ $item->config->rate }}%
                                        @elseif($item->config->rate_type === 'fixed')
                                            @if($item->config->rate_usd)
                                                {{ $item->config->rate_usd }} USD
                                            @else
                                                {{ $item->config->rate == 0 ? 1 : $item->config->rate }} {{ $item->config->currency ?? 'N/A' }}
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->vat, 1) }}</td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                        </tbody>
                        <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>{{ number_format($return->total_amount_due, 2) }}</th>
                        </tr>

                        </tfoot>
                    </table>
                </div>
                <div class="tab-pane p-2" id="penalties" role="tabpanel" aria-labelledby="penalties-tab">
                    <table class="table table-bordered table">
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
                                        <strong>{{ $return->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['late_filing'], 2) }}
                                        <strong>{{ $return->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['late_payment'], 2) }}
                                        <strong>{{ $return->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['rate_percentage'], 4) }}
                                        <strong>%</strong></td>
                                    <td>{{ number_format($penalty['rate_amount'], 2) }}
                                        <strong>{{ $return->currency }}</strong>
                                    </td>
                                    <td>{{ number_format($penalty['penalty_amount'], 2) }}
                                        <strong>{{ $return->currency }}</strong>
                                    </td>
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
