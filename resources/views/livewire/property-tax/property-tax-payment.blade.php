@if(!empty($payment->latestBill))
    <div class="row py-4 alert alert-secondary bg-alt rounded-0 shadow-sm border-success">
        <div class="col-md-4">
            <span class="font-weight-bold text-uppercase">
                @if ($payment->payment_status === \App\Models\Returns\ReturnStatus::COMPLETE)
                    Total Tax Paid
                @else
                    Total Tax Payable
                @endif
            </span>
            <p class="my-1">{{ number_format($payment->latestBill->amount, 2) }} {{ $payment->latestBill->currency }}</p>
        </div>
        @if ($payment->payment_status === \App\Models\Returns\ReturnStatus::CN_GENERATED ||
            $payment->payment_status === \App\Models\Returns\ReturnStatus::PAID_PARTIALLY)

            @if($payment->latestBill->zan_trx_sts_code == \App\Services\ZanMalipo\ZmResponse::SUCCESS)
                <div class="col-md-4" wire:poll.visible.30000ms="refresh" wire:poll.30000ms>
                    <span class="font-weight-bold text-uppercase">Control No.</span>
                    <p class="my-1">{{ $payment->latestBill->control_number }}</p>
                </div>
                <div class="col-md-4">
                    <span class="font-weight-bold text-uppercase"> </span>
                    <p class="my-1">
                        <a target="_blank" href="{{ route('bill.invoice', encrypt($payment->latestBill->id)) }}" class="btn btn-primary btn-sm py-1 w-75 font-weight-bold">
                            <i class="bi bi-download mr-3"></i><u>Download Bill</u>
                        </a>
                    </p>
                    <button class="btn btn-secondary btn-sm py-1 w-75 font-weight-bold"
                        onclick="Livewire.emit('showModal', 'transfer-form.transfer-form-generator', '{{$payment->latestBill->currency}}', '{{  encrypt($payment->latestBill->id) }}')">
                        <i class="bi bi-file-earmark-text"></i>
                        Get Transfer Form
                    </button>
                </div>
            @else
                <div class="col-md-4">
                    <span class="font-weight-bold text-uppercase">Control No. Generation Failed</span>
                    <p class="my-1 text-danger">
                        Generation Failed
                    </p>
                </div>
            @endif
        @elseif($payment->payment_status === \App\Models\Returns\ReturnStatus::COMPLETE)
            <div class="col-md-4">
                <span class="font-weight-bold text-uppercase">Payment Status</span>
                <p class="my-1 text-success font-weight-bold">
                    <i class="bi bi-check-circle-fill mr-2"></i>
                    Paid
                </p>
            </div>
        @elseif($payment->payment_status === \App\Models\Returns\ReturnStatus::CN_GENERATING)
            <div class="col-md-4" wire:poll.visible="refresh">
                <span class="font-weight-bold text-uppercase text-info">Control No.</span>
                <p class="my-1 text-info">
                    <i class="bi bi-clock-history mr-2"></i>
                    Pending
                </p>
            </div>
        @elseif($payment->payment_status === \App\Models\Returns\ReturnStatus::CN_GENERATION_FAILED)
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
        <div class="col-md-12 mt-3">
            <span class="font-weight-bold text-uppercase">{{ __('ZanMalipo status') }}:</span>
            <span>
                {{ $this->getGepgStatus($payment->latestBill->zan_trx_sts_code) }}
            </span>
        </div>
    </div>
@else
    <div class="row py-4 alert alert-secondary bg-alt rounded-0 shadow-sm border-success">
        <div class="col-md-3">
            <span class="font-weight-bold text-uppercase">
                Total Tax Payable
            </span>
            <p class="my-1">{{ number_format($payment->total_amount, 2) }} {{ $payment->currency->iso }}
            </p>
        </div>
        @if ($payment->total_amount > 0)
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase">Bill Status</span>
                <p class="my-1 text-danger">
                    Generation Failed
                </p>
            </div>
            <div class="col-md-3">
                <p class="my-1">
                    <button target="_blank" wire:click="generateBill"
                        class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                        <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                            wire:target="generateBill"></i>
                        <i class="bi bi-arrow-repeat mr-2" wire:loading.remove wire:target="generateBill"></i>Generate
                        Bill
                    </button>
                </p>
            </div>
        @endif
    </div>
@endif
