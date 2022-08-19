<div class="row p-2">
    <div class="col-md-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block p-2">
                <p class="m-b-20 text-lg">Total Tax Amount Unpaid</p>

                <h5 class="text-right"><span>{{ number_format($this->totalTaxAmount, 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
            </div>
        </div>
    </div>

    {{-- <div class="col-md-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block p-2">
                <p class="m-b-20 text-lg">Total Principal Tax Amount Unpaid</p>

                <h3 cla5s="text-right"><span>{{ number_format($this->totalPrincipalAmount, 2) }}</span><span class="h6 ml-1">Tsh</span></h3>
            </div>
        </div>
    </div> --}}

    <div class="col-md-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block p-2">
                <p class="m-b-20 text-lg">Total Late Filing Unpaid</p>

                <h5 class="text-right"><span>{{ number_format($this->totalLateFiling, 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block p-2">
                <p class="m-b-20 text-lg">Total Late Payment Unpaid</p>

                <h5 class="text-right"><span>{{ number_format($this->totalLatePayment, 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block p-2">
                <p class="m-b-20 text-lg">Total Interest Unpaid</p>

                <h5 class="text-right"><span>{{ number_format($this->totalRate, 2) }}</span><span class="h6 ml-1">Tsh</span></h5>
            </div>
        </div>
    </div>
</div>