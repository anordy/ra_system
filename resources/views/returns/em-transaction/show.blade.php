@extends('layouts.master')

@section('title', 'View Electronic Money Transaction')

@section('content')
    <div class="card">
        <div class="card-body">
            <h6 class="text-uppercase mt-2 ml-2">{{ $return->taxtype->name }} Returns Details for
                {{ $return->financialMonth->name }},
                {{ $return->financialMonth->year->code }}</h6>
            <hr>

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
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Total</span>
                    <p class="my-1">{{ $return->currency }} {{ number_format($return->total_amount_due ?? 0, 2) }}</p>
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
                        @foreach ($return->configReturns as $item)
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
                                        {{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd ?? '-' }}
                                    </td>
                                    <td>{{ number_format($item->vat, 2) }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-12">
            <h6 class="text-uppercase mt-2 ml-2">Penalties</h6>
            <hr>
            <livewire:returns.returns-penalty modelName='App\Models\Returns\EmTransaction\EmTransactionReturn'
                modelId="{{ $returnId }}" />
        </div>
    @endsection
