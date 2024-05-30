<div class="row">
    <div class="col-md-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block p-2">
                <h6 class="text-lg">Total Tax Amount</h6>
                <div class="">
                    <div class="">
                        Paid:
                        <h5 class=""><span>{{ number_format($paidReturnsTZS['totalTaxAmount'] ?? \App\Enum\GeneralConstant::ZERO_INT, 2) }}</span><span
                                class="h6 ml-1">TZS</span>
                        </h5>
                        <h5 class=""><span>{{ number_format($paidReturnsUSD['totalTaxAmount'] ?? \App\Enum\GeneralConstant::ZERO_INT, 2) }}</span><span
                                class="h6 ml-1">USD</span>
                        </h5>
                    </div>
                    <div class="">
                        Unpaid:
                        <h5 class="">
                            <span>{{ number_format($unPaidReturnsTZS['totalTaxAmount'] ?? \App\Enum\GeneralConstant::ZERO_INT, 2) }}</span><span
                                class="h6 ml-1">TZS</span>
                        </h5>
                        <h5 class="">
                            <span>{{ number_format($unPaidReturnsUSD['totalTaxAmount'] ?? \App\Enum\GeneralConstant::ZERO_INT, 2) }}</span><span
                                class="h6 ml-1">USD</span>
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
                            <span>{{ number_format($paidReturnsTZS['totalLateFiling'] ?? \App\Enum\GeneralConstant::ZERO_INT, 2) }}</span><span
                                class="h6 ml-1">TZS</span>
                        </h5>
                        <h5 class="">
                            <span>{{ number_format($paidReturnsUSD['totalLateFiling'] ?? \App\Enum\GeneralConstant::ZERO_INT, 2) }}</span><span
                                class="h6 ml-1">USD</span>
                        </h5>
                    </div>
                    <div class="">
                        Unpaid:
                        <h5 class="">
                            <span>{{ number_format($unPaidReturnsTZS['totalLateFiling'] ?? \App\Enum\GeneralConstant::ZERO_INT, 2) }}</span><span
                                class="h6 ml-1">TZS</span>
                        </h5>
                        <h5 class="">
                            <span>{{ number_format($unPaidReturnsUSD['totalLateFiling'] ?? \App\Enum\GeneralConstant::ZERO_INT, 2) }}</span><span
                                class="h6 ml-1">USD</span>
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
                            <span>{{ number_format($paidReturnsTZS['totalLatePayment'] ?? \App\Enum\GeneralConstant::ZERO_INT, 2) }}</span><span
                                class="h6 ml-1">TZS</span>
                        </h5>
                        <h5 class="">
                            <span>{{ number_format($paidReturnsUSD['totalLatePayment'] ?? \App\Enum\GeneralConstant::ZERO_INT, 2) }}</span><span
                                class="h6 ml-1">USD</span>
                        </h5>
                    </div>
                    <div class="">
                        Unpaid:
                        <h5 class="">
                            <span>{{ number_format($unPaidReturnsTZS['totalLatePayment'] ?? \App\Enum\GeneralConstant::ZERO_INT, 2) }}</span><span
                                class="h6 ml-1">TZS</span>
                        </h5>
                        <h5 class="">
                            <span>{{ number_format($unPaidReturnsUSD['totalLatePayment'] ?? \App\Enum\GeneralConstant::ZERO_INT, 2) }}</span><span
                                class="h6 ml-1">USD</span>
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
                        <h5 class=""><span>{{ number_format($paidReturnsTZS['totalRate'] ?? \App\Enum\GeneralConstant::ZERO_INT, 2) }}</span><span
                                class="h6 ml-1">TZS</span>
                        </h5>
                        <h5 class=""><span>{{ number_format($paidReturnsUSD['totalRate'] ?? \App\Enum\GeneralConstant::ZERO_INT, 2) }}</span><span
                                class="h6 ml-1">USD</span>
                        </h5>
                    </div>
                    <div class="">
                        Unpaid:
                        <h5 class=""><span>{{ number_format($unPaidReturnsTZS['totalRate'] ?? \App\Enum\GeneralConstant::ZERO_INT, 2) }}</span><span
                                class="h6 ml-1">TZS</span>
                        </h5>
                        <h5 class=""><span>{{ number_format($unPaidReturnsUSD['totalRate'] ?? \App\Enum\GeneralConstant::ZERO_INT, 2) }}</span><span
                                class="h6 ml-1">USD</span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
