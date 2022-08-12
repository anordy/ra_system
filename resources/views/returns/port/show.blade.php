@extends('layouts.master')

@section('title', 'View Port Returns')

@section('content')
    <div class="card">
        <div class="card-body">
         <h6 class="text-uppercase mt-2 ml-2">{{ $return->taxtype->name }} Returns Details for {{ $return->financialMonth->name }},
                {{ $return->financialMonth->year->code }}</h6>
            <hr>

            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Type</span>
                    <p class="my-1">{{ $return->taxtype->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Filled By</span>
                    <p class="my-1">{{ $return->taxpayer->full_name ?? '' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Financial Year</span>
                    <p class="my-1">{{ $return->financialYear->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $return->business->name ?? '' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Location</span>
                    <p class="my-1">{{ $return->branch->name ?? 'Head Quarter' }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Payment Status</span>
                    <p class="my-1">
                        @if ($return->status === \App\Models\Returns\ReturnStatus::COMPLETE)
                            <span class="badge badge-success py-1 px-2"
                                  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                                Paid
                            </span>
                        @elseif($return->status === \App\Models\Returns\ReturnStatus::COMPLETED_PARTIALLY || $return->status === \App\Models\Returns\ReturnStatus::PAID_PARTIALLY)
                            <span class="badge badge-success py-1 px-2" style="border-radius: 1rem; background: rgba(220,206,53,0.35); color: #cfc61c;; font-size: 85%">
                                Partially Paid
                            </span>
                        @else($return->status === \App\Models\Returns\ReturnStatus::COMPLETED_PARTIALLY)
                            <span class="badge badge-success py-1 px-2" style="border-radius: 1rem; background: #dc354559; color: #cf1c2d;; font-size: 85%">
                                Not Paid
                            </span>
                        @endif
                    </p>
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
                        @foreach ($return->configReturns as $item)
                            <tr>
                                <td>{{ $item->config->name }}</td>
                                <td>{{ number_format($item->value) }}</td>
                                <td>
                                    @if ($item->config->rate_type == 'fixed')
                                        @if ($item->config->currency == 'both')
                                            {{ $item->config->rate }} TZS <br>
                                            {{ $item->config->rate_usd }} USD
                                        @elseif ($item->config->currency == 'TZS')
                                            {{ $item->config->rate }} TZS
                                        @elseif ($item->config->currency == 'USD')
                                            {{ $item->config->rate_usd }} USD
                                        @endif
                                    @elseif ($item->config->rate_type == 'percentage')
                                        {{ $item->config->rate }} %
                                    @endif
                                    {{-- {{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }} --}}
                                </td>
                                <td>{{ number_format($item->vat) }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>


             <div class="row">
                <div class="col-md-12">PAYMENT STRUCTURE</div>
                <div class="col-md-12">
                    <livewire:returns.port.returns-port-penalty modelName='App\Models\Returns\Port\PortReturn'
                        modelId="{{ $return->id }}" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 d-flex justify-content-end">
                    @if(\Carbon\Carbon::now()->lessThan($return->financialMonth->due_date))
                        <a class="btn btn-primary" href="{{ route('returns.port.edit', encrypt($return->id)) }}">
                            Edit Return
                        </a>
                    @endif

                    @if($return->status === \App\Models\Returns\ReturnStatus::SUBMITTED)
                        <a class="btn btn-primary ml-4" href="{{ route('returns.port.show', encrypt($return->id)) }}">
                            Generate Control No.
                        </a>
                    @endif
                </div>
            </div>
        </div>


    @endsection
