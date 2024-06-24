<div>
    @if ($leasePartialPayment->bills()->count() > 0)
        @if ($bill = $leasePartialPayment->bills()->latest()->first())
            <div class="row py-4 alert alert-secondary bg-alt rounded-0 shadow-sm border-success"
                 wire:poll.visible.10000ms="refresh" wire:poll.5000ms>
                <div class="col-md-4">
                    <span class="font-weight-bold text-uppercase">{{ __('Total Payment Amount') }}</span>
                    <p class="my-1">{{ number_format($bill->amount, 2) }} {{ $bill->currency }}</p>
                </div>

                @if ($leasePartialPayment->status === 'approved')
                    <div class="col-md-4">
                        <span class="font-weight-bold text-uppercase">Control No.</span>
                        <p class="my-1">{{ $bill->control_number }}</p>
                    </div>
                    <div class="col-md-4">
                        <span class="font-weight-bold text-uppercase"> </span>
                        <p class="my-1">
                            <a target="_blank" href="{{ route('bill.invoice', encrypt($bill->id)) }}"
                               class="btn btn-primary btn-sm py-1 w-75 font-weight-bold">
                                <i class="bi bi-download mr-3"></i><u>{{ __('Download Bill') }}</u>
                            </a>
                        </p>
                        <button class="btn btn-secondary btn-sm py-1 w-75 font-weight-bold"
                                onclick="Livewire.emit('showModal', 'transfer-form.transfer-form-generator', '{{ $bill->currency }}', '{{ $bill->id }}')">
                            <i class="bi bi-file-earmark-text"></i>
                            {{ __('Get Transfer Form') }}
                        </button>
                    </div>
                @elseif($leasePartialPayment->status === 'rejected')
                    <div class="col-md-4">
                        <span class="font-weight-bold text-uppercase">{{ __('Approval Status') }}</span>
                        <p class="my-1 text-success font-weight-bold">
                            <i class="bi bi-check-circle-fill mr-2"></i>
                            {{ __($leasePartialPayment->status) }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <span class="font-weight-bold text-uppercase">Reason</span>
                        <p class="my-1 text-success font-weight-bold">
                            <i class="bi bi-check-circle-fill mr-2"></i>
                            {{ __($leasePartialPayment->comments ?? '--') }}
                        </p>
                    </div>
                @endif
                @if ($bill->zan_trx_sts_code != \App\Services\ZanMalipo\ZmResponse::SUCCESS)
                    <div class="col-md-4">
                        <span class="font-weight-bold text-uppercase">{{ __('Control No. Generation Failed') }}</span>
                        <p class="my-1 text-danger">
                            {{ __('Generation Failed') }}
                        </p>
                    </div>
                    <div class="col-md-4">
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
                        {{ $this->getGepgStatus($bill->zan_trx_sts_code) }}
                    </span>
                </div>
            </div>
        @endif
    @else
        <div class="row py-4 alert alert-secondary bg-alt rounded-0 shadow-sm border-success">
            <div class="col-md-4">
                <span class="font-weight-bold text-uppercase">{{ __('Control No. Not Generated') }}</span>
                <p class="my-1 text-primary">
                    {{ __('Not Generated') }}
                </p>
            </div>
            <div class="col-md-4">
                <p class="my-1">
                    <button target="_blank" wire:click="generate"
                            class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                        <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                           wire:target="generate"></i>
                        <i class="bi bi-arrow-repeat mr-2" wire:loading.remove
                           wire:target="generate"></i>{{ __('Generate Control No') }}

                    </button>
                </p>
            </div>
        </div>
    @endif
</div>
