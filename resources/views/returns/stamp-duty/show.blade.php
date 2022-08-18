@extends('layouts.master')

@section('title', 'View Return')

@section('content')
    <div class="row mx-1">
        <div class="col-md-12">
            <livewire:returns.return-payment :return="$return" />
        </div>
    </div>

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Returns Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Type</span>
                    <p class="my-1">Stamp Duty</p>
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
                    <span class="font-weight-bold text-uppercase">Financial Month</span>
                    <p class="my-1">{{ $return->financialMonth->name }}</p>
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
        </div>
    </div>
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Return Structure
        </div>
        <div class="card-body">
            <table class="table table-bordered mb-0 table-sm table-hover">
                <thead>
                <th style="width: 30%">Item Name</th>
                <th style="width: 20%">Value(TZS)</th>
                <th style="width: 10%">Rate</th>
                <th style="width: 20%">Tax(TZS)</th>
                </thead>
                <tbody>
                @foreach ($return->items as $item)
                    <tr>
                        <td>{{ $item->config->name }}</td>
                        <td>{{ number_format($item->value) }}</td>
                        <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }}</td>
                        <td>{{ number_format($item->tax) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr class="bg-secondary">
                    <th style="width: 20%">Total</th>
                    <th style="width: 30%"></th>
                    <th style="width: 25%"></th>
                    <th style="width: 25%">{{ number_format($return->total_amount_due) }}</th>
                </tr>

                </tfoot>
            </table>
        </div>
    </div>

    @if($return->penalty)
        <div class="card rounded-0">
            <div class="card-header bg-white font-weight-bold">
                Return Penalties
            </div>
            <div class="card-body">
                <livewire:returns.returns-penalty modelName="App\Models\Returns\StampDuty\StampDutyReturn" modelId="{{ $return->id }}" />
            </div>
        </div>
    @endif

    <x-bill-structure :bill="$return->bill" />

@endsection
