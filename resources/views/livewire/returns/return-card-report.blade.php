
<div class="row">
    <div class="col-md-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block p-2">
                <h6 class="text-lg">Total Tax Amount</h6>
                <div class="">
                    <div class="">
                        Paid:
                        <h5 class=""><span>{{ number_format($this->totalTaxAmountPaid, 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
                    </div>
                    <div class="">
                        Unpaid:
                        <h5 class=""><span>{{ number_format($this->totalTaxAmountUnpaid, 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block p-2">
                <h6 class="m-b-20 text-lg">Total Late Filing</h6>

                <div class="">
                    <div class="">
                        Paid:
                        <h5 class=""><span>{{ number_format($this->totalLateFilingPaid, 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
                    </div>
                    <div class="">
                        Unpaid:
                        <h5 class=""><span>{{ number_format($this->totalLateFilingUnpaid, 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block p-2">
                <h6 class="m-b-20 text-lg">Total Late Payment</h6>
                
                <div class="">
                    <div class="">
                        Paid:
                        <h5 class=""><span>{{ number_format($this->totalLatePaymentPaid, 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
                    </div>
                    <div class="">
                        Unpaid:
                        <h5 class=""><span>{{ number_format($this->totalLatePaymentUnpaid, 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block p-2">
                <h6 class="m-b-20 text-lg">Total Interest</h6>

                <div class="">
                    <div class="">
                        Paid:
                        <h5 class=""><span>{{ number_format($this->totalRatePaid, 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
                    </div>
                    <div class="">
                        Unpaid:
                        <h5 class=""><span>{{ number_format($this->totalRateUnpaid, 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>