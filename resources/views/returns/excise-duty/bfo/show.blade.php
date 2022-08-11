@extends('layouts.master')

@section('title', 'View BFO Returns')

@section('content')
    <div class="card">
        <div class="card-body">
            <h6 class="text-uppercase mt-2 ml-2">Returns Details</h6>
            <hr>
            <div class="row m-2 pt-3">
                <div class="col-md-12">
                    <livewire:returns.return-payment :return="$return" />
                </div>
            </div>
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Type</span>
                    <p class="my-1">{{ $return->taxtype->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Filled By</span>
                    <p class="my-1">{{ $return->business->taxpayer->first_name.' ' .$return->business->taxpayer->middle_name.' ' .$return->business->taxpayer->last_name }}</p>
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
                    <p class="my-1">{{ $return->currency }} {{ number_format($return->total_amount_due_with_penalties ?? 0, 2) }}</p>
                </div>
            </div>

            <h6 class="text-uppercase mt-2 ml-2">Items</h6>
            <hr>
        </div>
        <div class="col-md-12">
            <div class="col-md-12">
                <table class="table table-bordered table-sm">
                    <thead>
                        <th style="width: 30%">Item Name</th>
                        <th style="width: 20%">Value ({{ $return->currency }})</th>
                        <th style="width: 10%">Rate</th>
                        <th style="width: 20%">VAT ({{ $return->currency }})</th>
                    </thead>
                    <tbody>
                        @foreach ($return->items as $item)
                            {{-- {{$item->bfoConfig->name}} --}}
                            <tr @if ($item->bfoConfig->col_type === 'total') class="table-active font-weight-bolder" @endif>
                                <td>
                                    {{ $item->bfoConfig->name }}
                                </td>
                                <td>
                                    {{ $item->bfoConfig->col_type === 'total' ? '-' : number_format($item->value, 2) }}
                                </td>
                                </td>
                                <td>
                                    {{ $item->bfoConfig->rate_type === 'percentage' ? $item->bfoConfig->rate : $item->bfoConfig->rate_usd ?? '-' }}
                                </td>
                                <td>{{ number_format($item->vat, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
                @if (count($return->bfoPenalties))
                    @foreach ($return->bfoPenalties as $penalty)
                        <tr>
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
    @endsection
