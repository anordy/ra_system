<div class="row">
    <div class="col-md-4">
        <div class="card bg-c-yellow order-card">
            <div class="card-block p-2">
                <span class="m-b-20">Pending Request</span>
                <h4 class="text-right mb-0"><i class="bi bi-arrow-up-left-circle f-left"></i><span>{{ $pending }}</span></h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-c-yellow order-card">
            <div class="card-block p-2">
                <span class="m-b-20">Approved Request</span>
                <h4 class="text-right mb-0"><i class="bi bi-check2-all f-left"></i><span>{{ $approved }}</span></h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-c-yellow order-card">
            <div class="card-block p-2">
                <span class="m-b-20">Rejected Request</span>
                <h4 class="text-right mb-0"><i class="bi bi-x-circle f-left"></i><span>{{ $rejected }}</span></h4>
            </div>
        </div>
    </div>

</div>