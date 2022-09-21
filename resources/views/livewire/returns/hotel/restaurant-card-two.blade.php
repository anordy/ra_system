<div class="row">
    <div class="col-md-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block p-2">
                <h6 class="text-lg">Total Tax Amount</h6>
                <div class="">
                    <div class="">
                        Paid:
                        <h5 class=""><span>{{ number_format($paidReturns['totalTaxAmount'], 2) }}</span><span
                                class="h6 ml-1">Tsh</span></h5>
                    </div>
                    <div class="">
                        Unpaid:
                        <h5 class="">
                            <span>{{ number_format($unPaidReturns['totalTaxAmount'], 2) }}</span><span
                                class="h6 ml-1">Tsh</span>
                        </h5>
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
                        <h5 class="">
                            <span>{{ number_format($paidReturns['totalLateFiling'], 2) }}</span><span
                                class="h6 ml-1">Tsh</span>
                        </h5>
                    </div>
                    <div class="">
                        Unpaid:
                        <h5 class="">
                            <span>{{ number_format($unPaidReturns['totalLateFiling'], 2) }}</span><span
                                class="h6 ml-1">Tsh</span>
                        </h5>
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
                        <h5 class="">
                            <span>{{ number_format($paidReturns['totalLatePayment'], 2) }}</span><span
                                class="h6 ml-1">Tsh</span>
                        </h5>
                    </div>
                    <div class="">
                        Unpaid:
                        <h5 class="">
                            <span>{{ number_format($unPaidReturns['totalLatePayment'], 2) }}</span><span
                                class="h6 ml-1">Tsh</span>
                        </h5>
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
                        <h5 class=""><span>{{ number_format($paidReturns['totalRate'], 2) }}</span><span
                                class="h6 ml-1">Tsh</span></h5>
                    </div>
                    <div class="">
                        Unpaid:
                        <h5 class=""><span>{{ number_format($unPaidReturns['totalRate'], 2) }}</span><span
                                class="h6 ml-1">Tsh</span></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
