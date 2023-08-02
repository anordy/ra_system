<div class="row">
    <div class="col-md-2">
        <div class="card bg-c-yellow order-card">
            <div class="card-block p-2">
                <span class="m-b-20">Filed Returns</span>
                <h4 class="text-right mb-0"><i class="bi bi-building f-left"></i><span>{{ number_format($totalSubmittedReturns) }}</span></h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-c-yellow order-card">
            <div class="card-block p-2">
                <span class="m-b-20">Late Filed Returns</span>
                <h4 class="text-right mb-0"><i class="bi bi-building f-left"></i><span>{{ number_format($totalLateFiledReturns) }}</span></h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-c-yellow order-card">
            <div class="card-block p-2">
                <span class="m-b-20">In-Time Filed Returns</span>
                <h4 class="text-right mb-0"><i class="bi bi-building f-left"></i><span>{{ number_format($totalInTimeFiledReturns) }}</span></h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-c-yellow order-card">
            <div class="card-block p-2">
                <span class="m-b-20">Paid Returns</span>
                <h4 class="text-right mb-0"><i class="bi bi-building f-left"></i><span>{{ number_format($totalPaidReturns) }}</span></h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-c-yellow order-card">
            <div class="card-block p-2">
                <span class="m-b-20">Unpaid Returns</span>
                <h4 class="text-right mb-0"><i class="bi bi-building f-left"></i><span>{{ number_format($totalUnpaidReturns) }}</span></h4>
            </div>
        </div>
    </div>
   
    <div class="col-md-2">
        <div class="card bg-c-yellow order-card">
            <div class="card-block p-2">
                <span class="m-b-20">Late Paid Returns</span>
                <h4 class="text-right mb-0"><i class="bi bi-building f-left"></i><span>{{ number_format($totalLatePaidReturns) }}</span></h4>
            </div>
        </div>
    </div>
</div>