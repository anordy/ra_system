@extends('layouts.master')

@section('title')
    Vat Returns
@endsection
@section('stylesheet')
    <style>
        .tab-content {
            padding: 10px;
            background: #fff;
            box-shadow: rgb(0 0 0 / 16%) 0px 1px 4px;
        }
    </style>
@endsection

@section('content')
    <div>
        <div class="d-flex justify-content-end mb-3">
            <a href="{{route('returns.index')}}" class="btn btn-info">Back</a>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- {{ dd($return, $return->items) }} --}}
                {{-- {{ dd($return->items) }} --}}
                <h6 class="text-uppercase mt-2 ml-2">Returns Details</h6>
                <hr>

                <div class="row m-2 pt-3">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Tax Type</span>
                        <p class="my-1">{{ $return->taxtype->name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Filled By</span>
                        <p class="my-1">{{ $return->business->taxpayer->first_name.' ' .$return->business->taxpayer->last_name}}</p>
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

                <h6 class="text-uppercase mt-2 ml-2">Items</h6>
                <hr>

                <div class="col-md-12">
                    <table class="table table-bordered table-sm">
                        <thead>
                        <th style="width: 30%">Item Name</th>
                        <th style="width: 20%">Value</th>
                        <th style="width: 10%">Rate</th>
                        <th style="width: 20%">VAT</th>
                        </thead>
                        <tbody>
                        @foreach ($return->items as $item)
                            <tr>
                                <td>{{ $item->config->name }}</td>
                                <td>{{ number_format($item->value) }}</td>
                                <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }}
                                @if($item->config->rate_type =='percentage')
                                        %
                                    @endif
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
                            <th style="width: 25%">{{ number_format($return->total) }}</th>
                        </tr>

                        </tfoot>
                    </table>

                </div>
            </div>
    </div>
@endsection
