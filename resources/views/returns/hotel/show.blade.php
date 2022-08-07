@extends('layouts.master')

@section('title', 'View Hotel Returns')

@section('content')
    <div class="card">
        <div class="card-body">
            <h6 class="text-uppercase mt-2 ml-2">{{ $return->taxtype->name }} Returns Details for {{ $return->financialMonth->name }},
                {{ $return->financialMonth->year->code }}</h6>

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
                        <tfoot>
                            <tr>
                                <th style="width: 20%"></th>
                                <th style="width: 30%"></th>
                                <th style="width: 25%"></th>
                                <th style="width: 25%">{{ number_format($return->total_amount_due) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <p class="text-uppercase font-weight-bold">Payment Summary </p>
                </div>
                <div class="col-md-12">
                    <livewire:returns.returns-penalty modelName='App\Models\Returns\HotelReturns\HotelReturn'
                        modelId="{{ $return->id }}" />
                </div>
            </div>

        </div>



    @endsection
