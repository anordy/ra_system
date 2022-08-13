<div class="row">
    <div class="col-md-3">
        <div class="card bg-c-yellow order-card">
            <div class="card-block">
                <h6 class="m-b-20">Total Submitted</h6>
                <h2 class="text-right mb-0"><i class="bi bi-building f-left"></i><span>{{ $totalSubmittedReturns }}</span></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-c-yellow order-card">
            <div class="card-block">
                <h6 class="m-b-20">Total Paid</h6>
                <h2 class="text-right mb-0"><i class="bi bi-building f-left"></i><span>{{ $totalPaidReturns }}</span></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-c-yellow order-card">
            <div class="card-block">
                <h6 class="m-b-20">Total unpaid</h6>
                <h2 class="text-right mb-0"><i class="bi bi-building f-left"></i><span>{{ $totalUnpaidReturns }}</span></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-c-yellow order-card">
            <div class="card-block">
                <h6 class="m-b-20">Late Filings</h6>
                <h2 class="text-right mb-0"><i class="bi bi-building f-left"></i><span>{{ $totalLateFiledReturns }}</span></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-c-yellow order-card">
            <div class="card-block">
                <h6 class="m-b-20">Late Paid</h6>
                <h2 class="text-right mb-0"><i class="bi bi-building f-left"></i><span>{{ $totalLatePaidReturns }}</span></h2>
            </div>
        </div>
    </div>
</div>