@extends('layouts.master')

@section('title', 'View Excise Duty Returns for MNO')

@section('content')
<div class="card">
    <div class="card-body">        
        <h6 class="text-uppercase mt-2 ml-2">Returns Details</h6>
        <hr class="mx-2">

        <div class="row m-2 pt-3">
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Tax Type</span>
                <p class="my-1">{{ $return->taxType->name }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Filed By</span>
                <p class="my-1">{{ $return->taxpayer->full_name ?? '' }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Financial Year</span>
                <p class="my-1">{{ $return->financialYear->name ?? '' }}</p>
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

        <h6 class="text-uppercase mt-2 ml-2">Items</h6>
        <hr class="mx-2">

        <div class="col-md-12">
            <table class="table table-bordered table-sm">
                <thead>
                    <th style="width: 30%">Item Name</th>
                    <th style="width: 20%">Input Value </th>
                    <th style="width: 10%">Rate</th>
                    <th style="width: 20%">VAT</th>
                </thead>
                <tbody>
                    @foreach ($return->items as $item)
                    <tr class="{{ $item->config->col_type == 'total' ? 'table-active font-weight-bolder' : ''}}">
                        <td>{{ $item->config->name ?? 'name' }}</td>
                        <td>{{ $item->config->col_type =='total' ? '' : number_format($item->input_value) }}</td>
                        <td>{{ $item->config->rate_type ?? '' === 'percentage' ? $item->config->rate ?? '' :
                            $item->config->rate_usd ?? '' }}</td>
                        <td>{{ number_format($item->vat) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <div class="row m-2 pt-3">
            <h6 class="text-uppercase mt-2 ml-2">PAYMENT STRUCTURE</h6>
            <hr>
            <div class="col-md-12 p-0 m-0">

                <livewire:returns.returns-penalty modelName='App\Models\Returns\ExciseDuty\MnoReturn'
                    modelId="{{ $return->id }}" />
            </div>
        </div>
    </div>
@endsection