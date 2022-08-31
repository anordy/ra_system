@extends('layouts.master')

@section('title','Tax Credit')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Credit Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Payment Method</span>
                    <p class="my-1 text-uppercase">{{ $credit->payment_method }}</p>
                </div>
                @if($credit->payment_method == 'installment')
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Installment Count</span>
                        <p class="my-1 text-uppercase">{{ $credit->installments_count }}</p>
                    </div>
                @endif
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Credit Amount</span>
                    <p class="my-1">{{ $credit->currency }}. {{ number_format($credit->amount, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Type</span>
                    <p class="my-1">{{ $credit->taxType->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $credit->business->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Branch Location</span>
                    <p class="my-1">{{ $credit->location->name }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(count($credit->items))
        <div class="card rounded-0">
            <div class="card-header bg-white font-weight-bold">
                Tax Credit Items
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped w-100 table-bordered pb-0">
                            <thead>
                                <tr>
                                    <th width="10">SN.</th>
                                    <th>Amount</th>
                                    <th>Used For</th>
                                    <th>Used At.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($credit->items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->currency }}. {{ number_format($item->amount, 2) }}</td>
                                        <td>{{ $item->returnable->bill ? $item->returnable->bill->description : '' }}</td>
                                        <td>{{ $item->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection