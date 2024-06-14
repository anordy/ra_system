<div>
    @if ($showButton)
        <button wire:click="$set('showModal', true)" class="btn btn-primary">Forward to Investigation
            <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading></i>
        </button>
    @endif

    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="forwardToInvestigationLabel">Forward Tax Audit to Investigation</h5>
                        <button type="button" class="close" wire:click="$set('showModal', false)" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row m-2">
                            <div class="col-md-6 mb-3">
                                <span class="font-weight-bold text-uppercase">TIN</span>
                                <p class="my-1">{{ $taxAudit->business->tin ?? "" }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="font-weight-bold text-uppercase">Tax Type</span>
                                <p class="my-1">{{ $taxAudit->taxAuditTaxTypeNames() ?? "" }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="font-weight-bold text-uppercase">Business Name</span>
                                <p class="my-1">{{ $taxAudit->business->name ?? "" }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="font-weight-bold text-uppercase">Business Location</span>
                                <p class="my-1">{{ $taxAudit->taxAuditLocationNames() ?? "Head Quarter" }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="font-weight-bold text-uppercase">Auditing From</span>
                                <p class="my-1">{{ $taxAudit->period_from ?? "" }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="font-weight-bold text-uppercase">Auditing To</span>
                                <p class="my-1">{{ $taxAudit->period_to ?? "" }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="font-weight-bold text-uppercase">Scope</span>
                                <p class="my-1">{{ $taxAudit->scope ?? "" }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="font-weight-bold text-uppercase">Intension</span>
                                <p class="my-1">{{ $taxAudit->intension ?? "" }}</p>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)" aria-label="Close">
                            <span aria-hidden="true">Close</span>
                        </button>
                        <button wire:click="forward" class="btn btn-primary">Forward</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
