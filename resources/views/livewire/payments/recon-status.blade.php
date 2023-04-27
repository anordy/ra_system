<div class="card rounded-0">
    <div class="card-header font-weight-bold text-uppercase">
        Reconciliation Information
    </div>

    <div class="row m-4">
        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Reconciliation As On</span>
            <p class="my-1">{{ $recon->tnxdt }}</p>
        </div>
        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Reconciliation Type</span>
            <p class="my-1">
                @if ($recon->reconcopt == 1)
                    ZanMalipo Successful Transactions
                @elseif ($recon->reconcopt == 2)
                    Exception Transaction report after reconciliation between ZanMalipo and payment service provider
                @else
                    N/A
                @endif
        </div>
        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Reconciliation Enquired On</span>
            <p class="my-1">{{ $recon->created_at }}</p>
        </div>
        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">ZanMalipo Status</span>
            <p class="my-1">
                @if ($recon->reconcstscode)
                    {{ $this->getGepgStatus($recon->reconcstscode) }}
                @else
                    Pending
                @endif
            </p>
        </div>
    </div>

</div>