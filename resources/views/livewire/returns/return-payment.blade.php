<div class="row py-4 alert alert-success rounded-0">
    <div class="col-md-4">
        <span class="font-weight-bold text-uppercase">Total Payment Amount</span>
        <p class="my-1">{{ number_format($return->bill->amount, 2) }} TZS</p>
    </div>
    @if($return->status === \App\Models\Returns\ReturnStatus::CN_GENERATED || $return->status === \App\Models\Returns\ReturnStatus::PAID_PARTIALLY)
        <div class="col-md-4" wire:poll.visible.10000ms="refresh">
            <span class="font-weight-bold text-uppercase">Control No.</span>
            <p class="my-1">{{ $return->bill->control_number }}</p>
        </div>
    @elseif($return->status === \App\Models\Returns\ReturnStatus::COMPLETE)
        <div class="col-md-4">
            <span class="font-weight-bold text-uppercase">Payment Status</span>
            <p class="my-1 text-success font-weight-bold">
                <i class="bi bi-check-circle-fill mr-2"></i>
                Payment Complete
            </p>
        </div>
    @elseif($return->status === \App\Models\Returns\ReturnStatus::CN_GENERATING)
        <div class="col-md-4" wire:poll.visible="refresh">
            <span class="font-weight-bold text-uppercase text-info">Control No.</span>
            <p class="my-1 text-info">
                <i class="bi bi-clock-history mr-2"></i>
                Pending
            </p>
        </div>
    @elseif($return->status === \App\Models\Returns\ReturnStatus::CN_GENERATION_FAILED)
        <div class="col-md-4">
            <span class="font-weight-bold text-uppercase">Control No. Generation Failed</span>
            <p class="my-1 text-danger">
                Generation Failed
            </p>
        </div>
        <div class="col-md-4">
            <p class="my-1">
                <button target="_blank" wire:click="regenerate" class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                    <i class="spinner-border spinner-border-xs mr-2" role="status" wire:loading wire:target="regenerate"></i>
                    <i class="bi bi-arrow-repeat mr-2" wire:loading.remove wire:target="regenerate"></i>Regenerate Control No
                </button>
            </p>
        </div>
    @endif
</div>