@extends('layouts.master')

@section('title', 'View BFO Tax Return')

@section('content')
    <div class="row mx-1">
        <div class="col-md-12">
            <livewire:returns.return-payment :return="$return->tax_return" />
        </div>
    </div>
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            BFO Return Details
        </div>
        <div class="card-body">
            <ul  class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="bill-summary-tab" data-toggle="tab" href="#bill" role="tab"
                       aria-controls="bill" aria-selected="false">Bill Summary</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="bussiness-tab" data-toggle="tab" href="#bussiness" role="tab"
                       aria-controls="bussiness" aria-selected="true">Business Details</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="items-tab" data-toggle="tab" href="#items" role="tab" aria-controls="items"
                       aria-selected="false">Return Items</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="penalties-tab" data-toggle="tab" href="#penalties" role="tab"
                       aria-controls="penalties" aria-selected="false">Penaties</a>
                </li>
            </ul>
            <div  class="tab-content" id="myTabContent">
                <div class="tab-pane p-2 show active" id="bill" role="tabpanel" aria-labelledby="bill-tab">
                    <x-bill-structure :bill="$return->tax_return->latestBill" :withCard="false"/>
                </div>
                <div class="tab-pane p-2 show" id="bussiness" role="tabpanel" aria-labelledby="bussiness-tab">
                    <div class="row m-2 pt-3">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Tax Type</span>
                            <p class="my-1">{{ $return->taxtype->name }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Filled By</span>
                            <p class="my-1">
                                {{ $return->business->taxpayer->first_name . ' ' . $return->business->taxpayer->middle_name . ' ' . $return->business->taxpayer->last_name }}
                            </p>
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
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Total</span>
                            <p class="my-1">{{ $return->currency }}
                                {{ number_format($return->total_amount_due_with_penalties ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="tab-pane p-2 show" id="items" role="tabpanel" aria-labelledby="items-tab">
                    <table class="table table-bordered table-sm">
                        <thead>
                        <th>Excise Duty Payable Service</th>
                        <th>Value ({{ $return->currency }})</th>
                        <th>Rate</th>
                        <th>VAT ({{ $return->currency }})</th>
                        </thead>
                        <tbody>
                        @foreach ($return->items as $item)
                            @if ($item->config == null)

                            @else
                                <tr @if ($item->config->col_type === 'total') class="table-active font-weight-bolder" @endif>
                                    <td>
                                        {{ $item->config->name }}
                                    </td>
                                    <td>
                                        {{ $item->config->col_type === 'total' ? '-' : number_format($item->value, 2) }}
                                    </td>
                                    </td>
                                    <td>
                                        {{ $item->config->rate_type === 'percentage' ? $item->config->rate . '%' : $item->config->rate_usd .''. $item->config->currency ?? '-' }}
                                    </td>
                                    <td>
                                        @if ($item->vat)
                                            {{ number_format($item->vat, 2) }}
                                        @else
                                            0
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane p-2 show" id="penalties" role="tabpanel" aria-labelledby="penalties-tab">
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
            <div class="row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <a href="{{ route('returns.print', encrypt($return->tax_return->id)) }}" target="_blank" class="btn btn-info">
                        <i class="bi bi-printer-fill mr-2"></i>
                        Print Return
                    </a>
                </div>
            </div>
        </div>
    @endsection
