<div class="row">
    <div class="col-md-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block p-2">
                <p class="m-b-20 text-lg">Total Tax Amount Unpaid</p>
                
                <h5 class="text-right"><span>{{ number_format($data['totalTaxAmountTZS'], 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
                <h5 class="text-right"><span>{{ number_format($data['totalTaxAmountUSD'], 2) }}</span><span class="h6 ml-1">Usd</span></h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block p-2">
                <p class="m-b-20 text-lg">Total Late Filing Unpaid</p>
                
                <h5 class="text-right"><span>{{ number_format($data['totalLateFilingTZS'], 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
                <h5 class="text-right"><span>{{ number_format($data['totalLateFilingUSD'], 2) }}</span><span class="h6 ml-1">Usd</span></h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block p-2">
                <p class="m-b-20 text-lg">Total Late Payment Unpaid</p>
                
                <h5 class="text-right"><span>{{ number_format($data['totalLatePaymentTZS'], 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
                <h5 class="text-right"><span>{{ number_format($data['totalLatePaymentUSD'], 2) }}</span><span class="h6 ml-1">Usd</span></h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block p-2">
                <p class="m-b-20 text-lg">Total Interest Unpaid</p>
                
                <h5 class="text-right"><span>{{ number_format($data['totalRateTZS'], 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
                <h5 class="text-right"><span>{{ number_format($data['totalRateUSD'], 2) }}</span><span class="h6 ml-1">Usd</span></h5>
            </div>
        </div>
    </div>
</div>