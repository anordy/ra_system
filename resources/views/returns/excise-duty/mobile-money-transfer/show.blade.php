@extends('layouts.master')

@section('title', 'View Mobile Money Transfer Tax Return')

@section('content')
    <div class="row mx-2 pt-3">
        <div class="col-md-12">
            <livewire:returns.return-payment :return="$return->tax_return"/>
        </div>
    </div>
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            {{ $return->taxtype->name ?? 'N/A' }} Returns Details for
            {{ $return->financialMonth->name ?? 'N/A' }},
            {{ $return->financialMonth->year->code ?? 'N/A' }}
        </div>
        <div class="card-body">
            <div>
                <ul class="nav nav-tabs" id="myTab">
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
                <div class="tab-content" id="myTabContent">
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
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Total</span>
                                <p class="my-1">{{ $return->currency }} {{ number_format($return->total_amount_due_with_penalties ?? 0, 2) }}</p>
                            </div>
                        </div>

                        <x-bill-structure :bill="$return->tax_return->latestBill" :withCard="false"/>

                    </div>

                    <div class="tab-pane p-2" id="return-items" role="tabpanel" aria-labelledby="return-items-tab">
                        <table class="table table-bordered table-responsive table-sm">
                            <thead>
                            <th>Item Name</th>
                            <th>Value ({{ $return->currency }})</th>
                            <th>Rate</th>
                            <th>VAT ({{ $return->currency }})</th>
                            </thead>
                            <tbody>
                            @if(!empty($return->MmTransferReturnItems))
                                @foreach ($return->MmTransferReturnItems as $item)

                                    <tr @if ($item->config->col_type === 'total') class="table-active font-weight-bolder" @endif>
                                        <td>
                                            {{ $item->config->name }}
                                        </td>
                                        <td>
                                            {{ $item->config->col_type === 'total' ? '-' : number_format($item->value, 2) }}
                                        </td>
                                        </td>
                                        <td>
                                            {{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd ?? '-' }}
                                        </td>
                                        <td>{{ number_format($item->vat, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane p-2" id="penalties" role="tabpanel" aria-labelledby="penalties-tab">
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
                            @if(count($return->penalties))
                                @foreach ($return->penalties as $penalty)
                                    <tr>
                                        <td>{{ $penalty['financial_month_name'] }}</td>
                                        <td>{{ number_format($penalty['tax_amount'], 2) }}
                                            <strong>{{ $return->currency}}</strong></td>
                                        <td>{{ number_format($penalty['late_filing'], 2) }}
                                            <strong>{{ $return->currency}}</strong></td>
                                        <td>{{ number_format($penalty['late_payment'], 2) }}
                                            <strong>{{ $return->currency}}</strong></td>
                                        <td>{{ number_format($penalty['rate_percentage'], 4) }}</td>
                                        <td>{{ number_format($penalty['rate_amount'], 2) }}
                                            <strong>{{ $return->currency}}</strong></td>
                                        <td>{{ number_format($penalty['penalty_amount'], 2)}}
                                            <strong>{{ $return->currency}}</strong></td>
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
            <div class="row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <a href="{{ route('returns.print', encrypt($return->tax_return->id)) }}" target="_blank"
                       class="btn btn-info">
                        <i class="bi bi-printer-fill mr-2"></i>
                        Print Return
                    </a>
                </div>
            </div>
        </div>
@endsection
