@extends('layouts.master')

@section('title','Tax Claims')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Claim Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $claim->business->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Financial Month</span>
                    <p class="my-1">{{ $claim->financialMonth->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Financial Year</span>
                    <p class="my-1">{{ $claim->financialMonth->year->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Claim Status</span>
                    <p class="my-1">{{ ucfirst($claim->status) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Claimed Amount</span>
                    <p class="my-1">{{ number_format($claim->amount, 2) }} {{ $claim->currency }}</p>
                </div>

                <div class="col-md-12 mt-3">
                    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="old-return-tab" data-toggle="tab" href="#old-return" role="tab" aria-controls="old-return" aria-selected="true">Filed Return</a>
                        </li>
                        @if($newReturn)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="new-return-tab" data-toggle="tab" href="#new-return" role="tab" aria-controls="new-return" aria-selected="false">New Return</a>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content bg-white border shadow-sm" id="myTabContent" style="padding: 1rem !important;">
                        <div class="tab-pane fade  show active" id="old-return" role="tabpanel" aria-labelledby="old-return-tab">
                            <table class="table table-bordered normal-text">
                                <thead>
                                <th style="width: 30%">Item Name</th>
                                <th style="width: 20%">Value (TZS)</th>
                                <th style="width: 10%">Rate</th>
                                <th style="width: 20%">Tax (TZS)</th>
                                </thead>
                                <tbody>
                                @foreach ($oldReturn->items as $item)
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
                                    <th class="font-weight-bold" style="width: 20%">Total</th>
                                    <th style="width: 30%"></th>
                                    <th style="width: 25%"></th>
                                    <th style="width: 25%">{{ number_format($oldReturn->total_amount_due) }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        @if($newReturn)
                            <div class="tab-pane fade" id="new-return" role="tabpanel" aria-labelledby="new-return-tab">
                                <table class="table table-bordered normal-text">
                                    <thead>
                                    <th style="width: 30%">Item Name</th>
                                    <th style="width: 20%">Value (TZS)</th>
                                    <th style="width: 10%">Rate</th>
                                    <th style="width: 20%">Tax (TZS)</th>
                                    </thead>
                                    <tbody>
                                    @foreach ($newReturn->items as $item)
                                        <tr>
                                            <td>{{ $item->config->name }}</td>
                                            <td>{{ number_format($item->value) }}</td>
                                            <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }}</td>
                                            <td>{{ number_format($item->tax) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr class="bg-alt">
                                        <th style="width: 20%"></th>
                                        <th style="width: 30%"></th>
                                        <th style="width: 25%"></th>
                                        <th style="width: 25%">{{ number_format($newReturn->total_amount_due) }}</th>
                                    </tr>

                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection