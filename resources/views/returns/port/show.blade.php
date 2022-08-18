@extends('layouts.master')

@section('title', 'View Port Returns')

@section('content')
    <div>
        {{-- <a wire:click="back()" href="{{ route('returns.filing') }}" class="btn btn-info px-3 mb-2" type="button">
            <i class="bi bi-arrow-return-left mr-2"></i>
            Back
        </a> --}}
    </div>
    <div class="card">
        <div class="card-body">
            <h6 class="text-uppercase mt-2 ml-2">{{ $return->taxtype->name }} Returns Details for
                {{ $return->financialMonth->name }},
                {{ $return->financialMonth->year->code }}</h6>
            <hr>
       

            <div>
                <ul style="border-bottom: unset !important;" class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#biz" role="tab"
                            aria-controls="home" aria-selected="true">Business Details</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="academic-tab" data-toggle="tab" href="#academic" role="tab"
                            aria-controls="profile" aria-selected="false">Return Items</a>
                    </li>
                    @if ($return->application_status != App\Enum\ReturnApplicationStatus::CLAIM)
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="penalty-tab" data-toggle="tab" href="#penalty" role="tab"
                                aria-controls="penalty" aria-selected="false">Penalties</a>
                        </li>
                    @endif
                </ul>
                <div style="border: 1px solid #eaeaea;" class="tab-content" id="myTabContent">

                    <div class="tab-pane p-2 show active" id="biz" role="tabpanel" aria-labelledby="biz-tab">
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
                                    @elseif($return->status === \App\Models\Returns\ReturnStatus::COMPLETED_PARTIALLY ||
                                        $return->status === \App\Models\Returns\ReturnStatus::PAID_PARTIALLY)
                                        <span class="badge badge-success py-1 px-2"
                                            style="border-radius: 1rem; background: rgba(220,206,53,0.35); color: #cfc61c;; font-size: 85%">
                                            Partially Paid
                                        </span>
                                    @else($return->status === \App\Models\Returns\ReturnStatus::COMPLETED_PARTIALLY)
                                        <span class="badge badge-success py-1 px-2"
                                            style="border-radius: 1rem; background: #dc354559; color: #cf1c2d;; font-size: 85%">
                                            Not Paid
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- @if ($return->application_status != App\Enum\ReturnApplicationStatus::CLAIM) --}}
                        <div class="row m-2 pt-3">
                            <h6>Payment Structure</h6>
                        </div>

                     <div class="row">
                <div class="col-md-12">PAYMENT STRUCTURE</div>
                <div class="col-md-12">
                    <livewire:returns.port.returns-port-penalty modelName='App\Models\Returns\Port\PortReturn'
                        modelId="{{ $return->id }}" />
                </div>
            </div>

                        {{-- @endif --}}

                    </div>

                    <div class="tab-pane p-2" id="academic" role="tabpanel" aria-labelledby="academic-tab">
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
                        </div>
                    </div>

                    @if ($return->application_status != App\Enum\ReturnApplicationStatus::CLAIM)
                        <div class="tab-pane p-2" id="penalty" role="tabpanel" aria-labelledby="penalty-tab">
                            <div class="row">
                                <div class="col-md-12">PAYMENT STRUCTURE</div>
                                <div class="col-md-12">
                                    <livewire:returns.port.returns-port-penalty
                                        modelName='App\Models\Returns\Port\PortReturn' modelId="{{ $return->id }}" />
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection
