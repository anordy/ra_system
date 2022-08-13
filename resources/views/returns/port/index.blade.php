@extends('layouts.master')

@section('title','Port Return')

@section('content')
<div class="card p-0 m-0 mb-3">
    <div class="card-header text-uppercase">
        Summary
    </div>
    <div class="card-body mt-0 p-2">
        @livewire('returns.return-summary',['vars'=>$vars])
    </div>
</div>

    <div class="card">
        
        <div class="row p-2">
            <div class="col-md-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <p class="m-b-20 text-lg">Total Tax Amount Unpaid</p>
                        
                        <h5 class="text-right"><span>{{ number_format($data['totalTaxAmountTZS'], 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
                    </div>
                </div>
            </div>
        
            <div class="col-md-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <p class="m-b-20 text-lg">Total Late Filing Unpaid</p>
                        
                        <h5 class="text-right"><span>{{ number_format($data['totalLateFilingTZS'], 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
                    </div>
                </div>
            </div>
        
            <div class="col-md-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <p class="m-b-20 text-lg">Total Late Payment Unpaid</p>
                        
                        <h5 class="text-right"><span>{{ number_format($data['totalLatePaymentTZS'], 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
                    </div>
                </div>
            </div>
        
            <div class="col-md-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <p class="m-b-20 text-lg">Total Interest Unpaid</p>
                        
                        <h5 class="text-right"><span>{{ number_format($data['totalRateTZS'], 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <p class="m-b-20 text-lg">Total Tax Amount Unpaid</p>
                        
                        <h5 class="text-right"><span>{{ number_format($data['totalTaxAmountUSD'], 2) }}</span><span class="h6 ml-1">USD</span></h5>
                    </div>
                </div>
            </div>
        
            <div class="col-md-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <p class="m-b-20 text-lg">Total Late Filing Unpaid</p>
                        
                        <h5 class="text-right"><span>{{ number_format($data['totalLateFilingUSD'], 2) }}</span><span class="h6 ml-1">USD</span></h5>
                    </div>
                </div>
            </div>
        
            <div class="col-md-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <p class="m-b-20 text-lg">Total Late Payment Unpaid</p>
                        
                        <h5 class="text-right"><span>{{ number_format($data['totalLatePaymentUSD'], 2) }}</span><span class="h6 ml-1">USD</span></h5>
                    </div>
                </div>
            </div>
        
            <div class="col-md-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <p class="m-b-20 text-lg">Total Interest Unpaid</p>
                        
                        <h5 class="text-right"><span>{{ number_format($data['totalRateUSD'], 2) }}</span><span class="h6 ml-1">USD</span></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            @livewire('returns.port.port-return-table')
        </div>
    </div>
@endsection