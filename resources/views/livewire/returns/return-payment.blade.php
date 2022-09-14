@if(!empty($return->bill))
    <div class="row py-4 alert alert-secondary bg-alt rounded-0 shadow-sm border-success">
        <div class="col-md-4">
            <span class="font-weight-bold text-uppercase">
                @if ($return->payment_status === \App\Models\Returns\ReturnStatus::COMPLETE)
                    Total Tax Paid
                @else
                    Total Tax Payable
                @endif
            </span>
            <p class="my-1">{{ number_format($return->bill->amount, 2) }} {{ $return->bill->currency }}</p>
        </div>
        @if ($return->payment_status === \App\Models\Returns\ReturnStatus::CN_GENERATED ||
            $return->payment_status === \App\Models\Returns\ReturnStatus::PAID_PARTIALLY)

            @if($return->bill->zan_trx_sts_code == \App\Services\ZanMalipo\ZmResponse::SUCCESS)
                <div class="col-md-4" wire:poll.visible.10000ms="refresh" wire:poll.5000ms>
                    <span class="font-weight-bold text-uppercase">Control No.</span>
                    <p class="my-1">{{ $return->bill->control_number }}</p>
                </div>
                <div class="col-md-4">
                    <span class="font-weight-bold text-uppercase"> </span>
                    <p class="my-1">
                        <a target="_blank" href="{{ route('bill.invoice', encrypt($return->bill->id)) }}" class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                            <i class="bi bi-download mr-3"></i><u>Download Bill</u>
                        </a>
                    </p>
                </div>
            @else
                <div class="col-md-4">
                    <span class="font-weight-bold text-uppercase">Control No. Generation Failed</span>
                    <p class="my-1 text-danger">
                        Generation Failed
                    </p>
                </div>
            @endif
        @elseif($return->payment_status === \App\Models\Returns\ReturnStatus::COMPLETE)
            <div class="col-md-4">
                <span class="font-weight-bold text-uppercase">Payment Status</span>
                <p class="my-1 text-success font-weight-bold">
                    <i class="bi bi-check-circle-fill mr-2"></i>
                    Payment Complete
                </p>
            </div>
        @elseif($return->payment_status === \App\Models\Returns\ReturnStatus::CN_GENERATING)
            <div class="col-md-4" wire:poll.visible="refresh">
                <span class="font-weight-bold text-uppercase text-info">Control No.</span>
                <p class="my-1 text-info">
                    <i class="bi bi-clock-history mr-2"></i>
                    Pending
                </p>
            </div>
        @elseif($return->payment_status === \App\Models\Returns\ReturnStatus::CN_GENERATION_FAILED)
            <div class="col-md-4">
                <span class="font-weight-bold text-uppercase">Control No. Generation Failed</span>
                <p class="my-1 text-danger">
                    Generation Failed
                </p>
            </div>
            <div class="col-md-4">
                <p class="my-1">
                    <button target="_blank" wire:click="regenerate"
                            class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                        <i class="spinner-border spinner-border-xs mr-2" role="status" wire:loading
                           wire:target="regenerate"></i>
                        <i class="bi bi-arrow-repeat mr-2" wire:loading.remove wire:target="regenerate"></i>Regenerate
                        Control No
                    </button>
                </p>
            </div>
        @endif
    </div>
@endif