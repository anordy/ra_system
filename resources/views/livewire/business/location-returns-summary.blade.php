<div>
    <div class="row my-2 pt-1">
        <div class="col-md-3 mb-2">
            <span class="font-weight-bold text-uppercase">Total Amount</span>
            <p class="my-1">{{ number_format($total, 2) }} <b>TZS</b></p>
        </div>
        <div class="col-md-3 mb-2">
            <span class="font-weight-bold text-uppercase">Total Outstanding Amount</span>
            <p class="my-1">{{ number_format($outstanding, 2) }}</p>
        </div>
        <div class="col-md-3 mb-2">
            <span class="font-weight-bold text-uppercase">Total Paid Amount</span>
            <p class="my-1">{{ number_format($total - $outstanding, 2) }}</p>
        </div>
    </div>
    <div class="row my-2 pt-1">
        <div class="col-md-3 mb-2">
            <span class="font-weight-bold text-uppercase">Total Amount (USD)</span>
            <p class="my-1">{{ number_format($totalUSD, 2) }} <b>USD</b></p>
        </div>
        <div class="col-md-3 mb-2">
            <span class="font-weight-bold text-uppercase">Total Outstanding Amount (USD)</span>
            <p class="my-1">{{ number_format($outstandingUSD, 2) }} <b>USD</b></p>
        </div>
        <div class="col-md-3 mb-2">
            <span class="font-weight-bold text-uppercase">Total Paid Amount(USD)</span>
            <p class="my-1">{{ number_format($totalUSD - $outstandingUSD, 2) }}</p>
        </div>
    </div>
</div>