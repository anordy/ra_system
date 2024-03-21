@if (!empty($return->latestBill))
    <div class="row mx-1 py-4 alert alert-secondary bg-alt rounded-0 shadow-sm border-success"
         wire:poll.visible.10000ms="refresh" wire:poll.5000ms>
        <div class="col-md-3">
                <span class="font-weight-bold text-uppercase">
                    @if ($return->payment_status === \App\Models\Returns\ReturnStatus::COMPLETE)
                        {{ __('Total Tax Paid') }}
                    @else
                        {{ __('Total Tax Payable') }}
                    @endif
                </span>
            <p class="my-1">{{ number_format($return->latestBill->amount, 2) }} {{ $return->latestBill->currency }}
            </p>
        </div>
        @if ($return->latestBill->status == 'cancelled')
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase">{{ __('Bill Status') }}</span>
                <p class="my-1 text-danger" data-toggle="tooltip"
                   title="{{ $return->latestBill->cancellation_reason }}">
                    {{ __('Cancelled') }}
                </p>
            </div>
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase">{{ __('Cancellation Reason') }}</span>
                <p class="my-1" data-toggle="tooltip">
                    {{ $return->latestBill->cancellation_reason }}
                </p>
            </div>
        @endif
        <div class="col-md-3">
            <span class="font-weight-bold text-uppercase">Control No.</span>
            <p class="my-1">{{ $return->latestBill->control_number ?? '' }}</p>
        </div>
        @if ($return->payment_status === \App\Models\Returns\ReturnStatus::CN_GENERATED)
            @if (
                $return->latestBill->zan_trx_sts_code == \App\Services\ZanMalipo\ZmResponse::SUCCESS ||
                    $return->latestBill->zan_trx_sts_code == \App\Services\ZanMalipo\ZmResponse::ZM_DUPLICATE_BILL_INFO)
                <div class="col-md-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Expire Date') }}</span>
                    <p class="my-1">
                        {{ \Carbon\Carbon::parse($return->latestBill->expire_date)->format('d M Y H:i:s') ?? '' }}
                    </p>
                </div>
                <div class="col-md-3">
                    <p class="my-1 mb-2">
                        <a target="_blank" href="{{ route('bill.invoice', encrypt($return->latestBill->id)) }}"
                           class="btn btn-primary btn-sm py-1 w-75 font-weight-bold">
                            <i class="bi bi-download mr-3"></i><u>{{ __('Download Bill') }}</u>
                        </a>
                    </p>
                    <button class="btn btn-secondary btn-sm py-1 w-75 font-weight-bold"
                            onclick="Livewire.emit('showModal', 'transfer-form.transfer-form-generator', '{{ $return->latestBill->currency }}', '{{ $return->latestBill->id }}')">
                        <i class="bi bi-file-earmark-text"></i>
                        {{ __('Get Transfer Form') }}
                    </button>
                </div>
            @endif
        @elseif($return->payment_status === \App\Models\Returns\ReturnStatus::PAID_PARTIALLY)
            <div class="col-md-3" wire:poll.visible.10000ms="refresh" wire:poll.5000ms>
                <span class="font-weight-bold text-uppercase">Control No.</span>
                <p class="my-1">{{ $return->latestBill->control_number ?? '' }}</p>
            </div>
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase">{{ __('Payment Status') }}</span>
                <p class="my-1 text-info font-weight-bold">
                    <i class="bi bi-check-circle-fill mr-2"></i>
                    {{ __('Partially Paid') }}
                </p>
            </div>
        @elseif($return->payment_status === \App\Models\Returns\ReturnStatus::COMPLETE)
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase">{{ __('Payment Status') }}</span>
                <p class="my-1 text-success font-weight-bold">
                    <i class="bi bi-check-circle-fill mr-2"></i>
                    {{ __('Paid') }}
                </p>
            </div>
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase"> </span>
                <p class="my-1">
                    <a target="_blank" href="{{ route('bill.receipt', encrypt($return->latestBill->id)) }}"
                       class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                        <i class="bi bi-download mr-3"></i><u>{{ __('Download Receipt') }}</u>
                    </a>
                </p>
            </div>
        @elseif($return->payment_status === \App\Models\Returns\ReturnStatus::CN_GENERATING)
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase text-info">Control No.</span>
                <p class="my-1 text-info">
                    <i class="bi bi-clock-history mr-2"></i>
                    {{ __('Pending') }}
                </p>
            </div>
        @elseif(
            $return->payment_status === \App\Models\Returns\ReturnStatus::CN_GENERATION_FAILED ||
                (!$return->latestBill->zan_trx_sts_code && !$return->latestBill->control_number))
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase">Control No. {{ __('Generation Failed') }}</span>
                <p class="my-1 text-danger">
                    {{ __('Generation Failed') }}
                </p>
            </div>
            <div class="col-md-3">
                <p class="my-1">
                    <button target="_blank" wire:click="regenerate"
                            class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                        <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                           wire:target="regenerate"></i>
                        <i class="bi bi-arrow-repeat mr-2" wire:loading.remove
                           wire:target="regenerate"></i>{{ __('Regenerate Control No') }}

                    </button>
                </p>
            </div>
        @endif
        <div class="col-md-12 mt-3">
            <span class="font-weight-bold text-uppercase">{{ __('ZanMalipo status') }}:</span>
            <span>
                    {{ $this->getGepgStatus($return->latestBill->zan_trx_sts_code) }}
                </span>
        </div>
    </div>
@else
    <div class="row mx-1 py-4 alert alert-secondary bg-alt rounded-0 shadow-sm border-success">
            <div class="col-md-3">
            <span class="font-weight-bold text-uppercase">
                Total Tax Payable
            </span>
                <p class="my-1">{{ number_format($return->amount, 2) }} {{ $return->currency }}
                </p>
            </div>
        @if ($return->amount > 0)
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase">Bill Status</span>
                <p class="my-1">
                    No Bill Generated
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
