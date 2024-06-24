@extends('layouts.master')

@section('title', 'View Port Returns')

@section('content')
    <div class="card">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            {{ $return->taxtype->name ?? 'N/A' }} Returns Details for
            {{ $return->financialMonth->name ?? 'N/A' }},
            {{ $return->financialMonth->year->code ?? 'N/A' }}
        </div>
        <div class="card-body">
            <div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#biz" role="tab"
                            aria-controls="home" aria-selected="true">Business Details</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="academic-tab" data-toggle="tab" href="#academic" role="tab"
                            aria-controls="profile" aria-selected="false">Return Items</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane p-2 show active" id="biz" role="tabpanel" aria-labelledby="biz-tab">
                        <div class="row m-2 pt-3">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Tax Type</span>
                                <p class="my-1">{{ $return->taxtype->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Filed By</span>
                                <p class="my-1">{{ $return->taxpayer->full_name ?? '' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Financial Year</span>
                                <p class="my-1">{{ $return->financialYear->name ?? 'N/A' }}</p>
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
                                 @include('returns.return-payment-status', ['row' => $return])
                            </div>
                        </div>

                        <div class="row m-2 pt-3">
                            <h6>Payment Structure</h6>
                        </div>

                        <div class="row">
                            <div class="col-md-12">PAYMENT STRUCTURE</div>
                            <div class="col-md-12">
                                <livewire:returns.port.returns-port-penalty modelName='App\Models\Returns\Port\PortReturn'
                                    modelId="{{ encrypt($return->id) }}" />
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane p-2" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <p class="text-uppercase font-weight-bold">Return Items</p>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered table-responsive table-striped normal-text">
                                    <thead>
                                        <th>Item Name</th>
                                        <th>Value</th>
                                        <th>Rate</th>
                                        <th>Tax</th>
                                    </thead>
                                    <tbody>
                                    @if(!empty($return->configReturns))
                                        @foreach ($return->configReturns as $item)
                                            <tr>
                                                <td>{{ $item->config->name }}</td>
                                                <td>{{ number_format($item->value, 2) }}</td>
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
                                                <td>{{ number_format($item->vat, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(!empty($return->tax_return->id))
                <div class="row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <a href="{{ route('returns.print', encrypt($return->tax_return->id)) }}" target="_blank" class="btn btn-info">
                            <i class="bi bi-printer-fill mr-2"></i>
                            Print Return
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection
