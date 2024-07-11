@if ($bill = $payment->latestBill)
    <div class="row py-4 alert alert-secondary bg-alt rounded-0 shadow-sm border-success">
        <div class="col-md-3">
            <span class="font-weight-bold text-uppercase">
                @if ($bill->status === 'paid')
                    {{ __("Total Tax Paid") }}
                @else
                    {{ __("Total Tax Payable") }}
                @endif
            </span>
            <p class="my-1">{{ number_format($bill->amount, 2) }} {{ $bill->currency }}</p>
        </div>
        @if ($bill->status == "cancelled")
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase">Bill Status</span>
                <p class="my-1 text-danger" data-toggle="tooltip" title="{{ $bill->cancellation_reason }}">
                    {{ __("Cancelled") }}
                </p>
            </div>
        @endif
        @if ($bill->status === 'pending')
            @if (
                $bill->zan_trx_sts_code == \App\Services\ZanMalipo\ZmResponse::SUCCESS ||
                    $bill->zan_trx_sts_code == \App\Services\ZanMalipo\ZmResponse::ZM_DUPLICATE_BILL_INFO)
                <div class="col-md-3">
                    <span class="font-weight-bold text-uppercase">Control No.</span>
                    <p class="my-1">{{ $bill->control_number ?? "" }}</p>
                </div>
                <div class="col-md-3">
                    <span class="font-weight-bold text-uppercase">{{ __("Expire Date") }}</span>
                    <p class="my-1">{{ \Carbon\Carbon::parse($bill->expire_date)->format("d M Y H:i:s") ?? "" }}</p>
                </div>
                <div class="col-md-3">
                    <span class="font-weight-bold text-uppercase"> </span>
                    <p class="my-1">
                        <a target="_blank" href="{{ route("bill.invoice", encrypt($bill->id)) }}"
                           class="btn btn-primary btn-sm py-1 w-75 font-weight-bold">
                            <i class="bi bi-download mr-3"></i><u>{{ __("Download Bill") }}</u>
                        </a>
                    </p>
                    <button class="btn btn-secondary btn-sm py-1 w-75 font-weight-bold"
                            onclick="Livewire.emit('showModal', 'transfer-form.transfer-form-generator', '{{ $bill->currency }}', '{{ encrypt($bill->id) }}')">
                        <i class="bi bi-file-earmark-text"></i>
                        {{ __("Get Transfer Form") }}
                    </button>
                </div>
            @endif
        @elseif($bill->status === 'paid')
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase">Payment Status</span>
                <p class="my-1 text-success font-weight-bold">
                    <i class="bi bi-check-circle-fill mr-2"></i>
                    {{ __("Paid") }}
                </p>
            </div>
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase"> </span>
                <p class="my-1">
                    <a target="_blank" href="{{ route("bill.receipt", encrypt($bill->id)) }}"
                       class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                        <i class="bi bi-download mr-3"></i><u>{{ __("Download Receipt") }}</u>
                    </a>
                </p>
            </div>
        @elseif($bill->status === 'pending')
            <div class="col-md-3" wire:poll.visible="refresh">
                <span class="font-weight-bold text-uppercase text-info">Control No.</span>
                <p class="my-1 text-info">
                    <i class="bi bi-clock-history mr-2"></i>
                    {{ __("Pending") }}
                </p>
            </div>
        @elseif((!$bill->zan_trx_sts_code && !$bill->control_number))
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase">Control No. Generation Failed</span>
                <p class="my-1 text-danger">
                    {{ __("Generation Failed") }}
                </p>
            </div>
            <div class="col-md-3">
                <p class="my-1">
                    <button target="_blank" wire:click="regenerate"
                            class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                        <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                           wire:target="regenerate"></i>
                        <i class="bi bi-arrow-repeat mr-2" wire:loading.remove wire:target="regenerate"></i>
                        {{ __("Regenerate Control No") }}
                    </button>
                </p>
            </div>
        @endif

        <div class="col-md-12 mt-3">
            <span class="font-weight-bold text-uppercase">{{ __("ZanMalipo status") }}:</span>
            <span>
                {{ $this->getGepgStatus($bill->zan_trx_sts_code) }}
            </span>
        </div>
    </div>
@else
    <div class="row py-4 alert alert-secondary bg-alt rounded-0 shadow-sm border-success">
        <div class="col-md-3">
            <span class="font-weight-bold text-uppercase">
                Total Tax Payable
            </span>
            <p class="my-1">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}
            </p>
        </div>
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

    </div>
@endif
