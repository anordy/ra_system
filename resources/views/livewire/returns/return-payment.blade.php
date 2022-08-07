<div style="margin-left: 0; margin-right: 0;" class="row alert alert-success">
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Total Payment Amount</span>
        <p class="my-1">{{ number_format($return->bill->amount, 2) }} TZS</p>
    </div>
    @if($return->status === \App\Models\Returns\ReturnStatus::CN_GENERATED || $return->status === \App\Models\Returns\ReturnStatus::PAID_PARTIALLY)
        <div class="col-md-4 mb-3" wire:poll.visible="refresh">
            <span class="font-weight-bold text-uppercase">Control No.</span>
            <p class="my-1">{{ $return->bill->control_number }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase"> </span>
            <p class="my-1">
                <a target="_blank" href="{{ route('bill.invoice', encrypt($return->bill->id)) }}" class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                    <i class="bi bi-download mr-3"></i><u>Download Bill</u>
                </a>
            </p>
        </div>
    @elseif($return->status === \App\Models\Returns\ReturnStatus::COMPLETE)
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Payment Status</span>
            <p class="my-1 text-success font-weight-bold">
                <i class="bi bi-check-circle-fill mr-2"></i>
                Payment Complete
            </p>
        </div>
    @elseif($return->status === \App\Models\Returns\ReturnStatus::CN_GENERATING)
        <div class="col-md-4 mb-3" wire:poll.visible="refresh">
            <span class="font-weight-bold text-uppercase text-info">Control No.</span>
            <p class="my-1 text-info">
                <i class="bi bi-clock-history mr-2"></i>
                Pending
            </p>
        </div>
    @elseif($return->status === \App\Models\Returns\ReturnStatus::CN_GENERATION_FAILED)
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase text-info">Control No.</span>
            <p class="my-1 text-danger">Control No. Generation Failed</p>
        </div>
    @endif
</div>