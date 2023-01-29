<div>
    @if ($leasePayment->bills()->count() > 0)
        @if ($bill = $leasePayment->bills()->latest()->first())
            <div class="row py-4 alert alert-secondary bg-alt rounded-0 shadow-sm border-success">
                <div class="col-md-4">
                    <span class="font-weight-bold text-uppercase">Total Payment Amount</span>
                    <p class="my-1">{{ number_format($bill->amount, 2) }} {{ $bill->currency }}</p>
                </div>
                @if ($leasePayment->status === \App\Enum\LeaseStatus::CN_GENERATED ||
                    $leasePayment->status === \App\Enum\LeaseStatus::PAID_PARTIALLY)
                    <div class="col-md-4" wire:poll.visible.10000ms="refresh" wire:poll.5000ms>
                        <span class="font-weight-bold text-uppercase">Control No.</span>
                        <p class="my-1">{{ $bill->control_number }}</p>
                    </div>
                    <div class="col-md-4">
                        <span class="font-weight-bold text-uppercase"> </span>
                        <p class="my-1">
                            <a target="_blank" href="{{ route('bill.invoice', encrypt($bill->id)) }}"
                                class="btn btn-primary btn-sm py-1 w-75 font-weight-bold">
                                <i class="bi bi-download mr-3"></i><u>Download Bill</u>
                            </a>
                        </p>
                        <button class="btn btn-secondary btn-sm py-1 w-75 font-weight-bold"
                            onclick="Livewire.emit('showModal', 'transfer-form.transfer-form-generator', '{{ $bill->currency }}', '{{ encrypt($bill->id) }}')">
                            <i class="bi bi-file-earmark-text"></i>
                            Get Transfer Form
                        </button>
                    </div>
                @elseif($leasePayment->status === \App\Enum\LeaseStatus::IN_ADVANCE_PAYMENT ||
                    $leasePayment->status === \App\Enum\LeaseStatus::ON_TIME_PAYMENT ||
                    $leasePayment->status === \App\Enum\LeaseStatus::LATE_PAYMENT)
                    <div class="col-md-4">
                        <span class="font-weight-bold text-uppercase">Payment Status</span>
                        <p class="my-1 text-success font-weight-bold">
                            <i class="bi bi-check-circle-fill mr-2"></i>
                            Payment Complete
                        </p>
                    </div>
                    <div class="col-md-4">
                        <span class="font-weight-bold text-uppercase"> </span>
                        <p class="my-1">
                            <a target="_blank" href="{{ route('bill.receipt', encrypt($bill->id)) }}"
                                class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                                <i class="bi bi-download mr-3"></i><u>Download Receipt</u>
                            </a>
                        </p>
                    </div>
                @elseif($leasePayment->status === \App\Enum\LeaseStatus::CN_GENERATING)
                    <div class="col-md-4" wire:poll.visible="refresh">
                        <span class="font-weight-bold text-uppercase text-info">Control No.</span>
                        <p class="my-1 text-info">
                            <i class="bi bi-clock-history mr-2"></i>
                            Pending
                        </p>
                    </div>
                @elseif($leasePayment->status === \App\Enum\LeaseStatus::CN_GENERATION_FAILED)
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
                                <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                                    wire:target="regenerate"></i>
                                <i class="bi bi-arrow-repeat mr-2" wire:loading.remove
                                    wire:target="regenerate"></i>Regenerate Control No
                            </button>
                        </p>
                    </div>
                @elseif($leasePayment->status === \App\Enum\LeaseStatus::PENDING)
                    <div class="col-md-4">
                        <span class="font-weight-bold text-uppercase">Control No. Not Generated</span>
                        <p class="my-1 text-primary">
                            Not Generated
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p class="my-1">
                            <button target="_blank" wire:click="generate"
                                class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                                <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                                    wire:target="generate"></i>
                                <i class="bi bi-arrow-repeat mr-2" wire:loading.remove
                                    wire:target="generate"></i>Generate Control No
                            </button>
                        </p>
                    </div>
                @endif
                <div class="col-md-12 mt-3">
                    <span class="font-weight-bold text-uppercase">Gepg Status:</span>
                    <span>
                        {{ $this->getGepgStatus($bill->zan_trx_sts_code) }}
                    </span>
                </div>
            </div>
        @endif
    @else
        <div class="row py-4 alert alert-secondary bg-alt rounded-0 shadow-sm border-success">
            <div class="col-md-4">
                <span class="font-weight-bold text-uppercase">Control No. Not Generated</span>
                <p class="my-1 text-primary">
                    Not Generated
                </p>
            </div>
            <div class="col-md-4">
                <p class="my-1">
                    <button target="_blank" wire:click="generate"
                        class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                        <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                            wire:target="generate"></i>
                        <i class="bi bi-arrow-repeat mr-2" wire:loading.remove wire:target="generate"></i>Generate
                        Control No
                    </button>
                </p>
            </div>
        </div>
    @endif
</div>
