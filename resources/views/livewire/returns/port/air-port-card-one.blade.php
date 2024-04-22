<div class="row">
    <div class="col-md-2">
        <div class="card bg-c-yellow order-card">
            <div class="card-block p-2">
                <span class="m-b-20">Filed Returns</span>
                <h4 class="text-right mb-0"><i
                        class="bi bi-building f-left"></i><span>{{ number_format($vars['totalSubmittedReturns'] ?? \App\Enum\GeneralConstant::ZERO_INT) }}</span>
                </h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-c-yellow order-card">
            <div class="card-block p-2">
                <span class="m-b-20">Late Filed Returns</span>
                <h4 class="text-right mb-0"><i
                        class="bi bi-building f-left"></i><span>{{ number_format($vars['totalLateFiledReturns'] ?? \App\Enum\GeneralConstant::ZERO_INT) }}</span></h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-c-yellow order-card">
            <div class="card-block p-2">
                <span class="m-b-20">In-Time Filed Returns</span>
                <h4 class="text-right mb-0"><i
                        class="bi bi-building f-left"></i><span>{{ number_format($vars['totalInTimeFiledReturns'] ?? \App\Enum\GeneralConstant::ZERO_INT) }}</span></h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-c-yellow order-card">
            <div class="card-block p-2">
                <span class="m-b-20">Paid Returns</span>
                <h4 class="text-right mb-0"><i
                        class="bi bi-building f-left"></i><span>{{ number_format($vars['totalPaidReturns'] ?? \App\Enum\GeneralConstant::ZERO_INT )}}</span>
                </h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-c-yellow order-card">
            <div class="card-block p-2">
                <span class="m-b-20">Unpaid Returns</span>
                <h4 class="text-right mb-0"><i
                        class="bi bi-building f-left"></i><span>{{ number_format($vars['totalUnpaidReturns'] ?? \App\Enum\GeneralConstant::ZERO_INT) }}</span>
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card bg-c-yellow order-card">
            <div class="card-block p-2">
                <span class="m-b-20">Late Paid Returns</span>
                <h4 class="text-right mb-0"><i
                        class="bi bi-building f-left"></i><span>{{ number_format($vars['totalLatePaidReturns'] ?? \App\Enum\GeneralConstant::ZERO_INT) }}</span></h4>
            </div>
        </div>
    </div>
</div>
