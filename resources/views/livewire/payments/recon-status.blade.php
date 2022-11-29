<div class="card rounded-0" wire:poll.visible.5000ms="refresh" wire:poll.5000ms>
    <div class="card-header font-weight-bold text-uppercase">
        Reconciliation Information
    </div>

    <div class="row m-4">
        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Reconciliation As On</span>
            <p class="my-1">{{ $recon->TnxDt }}</p>
        </div>
        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Reconciliation Type</span>
            <p class="my-1"> {{ $recon->ReconcOpt == 1 ? 'Successful Trasactions' : 'Failed Transactions' }}</p>
        </div>
        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Reconciliation Enquired On</span>
            <p class="my-1">{{ $recon->created_at }}</p>
        </div>
        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">GEPG Status</span>
            <p class="my-1">
                @if ($recon->ReconcStsCode)
                    {{ $this->getGepgStatus($recon->ReconcStsCode) }}
                @else
                    Pending
                @endif
            </p>
        </div>
    </div>

</div>