<div class="px-3">
    @if($motorVehicle->origin === \App\Enum\MvrRegistrationStatus::STATUS_CHANGE)
            <div class="row py-4 alert alert-secondary bg-alt rounded-0 shadow-sm border-success">
                <div class="col-md-3">
                    <span class="font-weight-bold text-uppercase"> </span>
                    <p class="my-1">
                        <a target="_blank" href="{{ route('mvr.registration.certificate', encrypt($motorVehicle->id)) }}"
                           class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                            <i class="bi bi-download mr-3"></i><u>{{ __('Download Registration Certificate') }}</u>
                        </a>
                    </p>
                </div>
            </div>
    @else
        @if(!empty($motorVehicle->latestBill))
            <div class="row py-4 alert alert-secondary bg-alt rounded-0 shadow-sm border-success">
                <div class="col-md-4">
            <span class="font-weight-bold text-uppercase">
               Total Fee Paid
            </span>
                    <p class="my-1">{{ number_format($motorVehicle->latestBill->amount, 2) }} {{ $motorVehicle->latestBill->currency }}</p>
                </div>
                @if ($motorVehicle->payment_status === \App\Models\Returns\ReturnStatus::CN_GENERATED ||
                    $motorVehicle->payment_status === \App\Models\Returns\ReturnStatus::PAID_PARTIALLY)

                    @if($motorVehicle->latestBill->zan_trx_sts_code == \App\Services\ZanMalipo\ZmResponse::SUCCESS)
                        <div class="col-md-4">
                            <span class="font-weight-bold text-uppercase">Control No.</span>
                            <p class="my-1">{{ $motorVehicle->latestBill->control_number }}</p>
                        </div>
                        <div class="col-md-4">
                            <span class="font-weight-bold text-uppercase"> </span>
                            <p class="my-1">
                                <a target="_blank"
                                   href="{{ route('bill.invoice', encrypt($motorVehicle->latestBill->id)) }}"
                                   class="btn btn-primary btn-sm py-1 w-75 font-weight-bold">
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
                @elseif($motorVehicle->payment_status === \App\Models\Returns\ReturnStatus::COMPLETE)
                    <div class="col-md-4">
                        <span class="font-weight-bold text-uppercase">Payment Status</span>
                        <p class="my-1 text-success font-weight-bold">
                            <i class="bi bi-check-circle-fill mr-2"></i>
                            Paid
                        </p>
                    </div>
                @if(get_class($motorVehicle) != 'App\Models\MvrRegistrationStatusChange')
                    <div class="col-md-3">
                        <span class="font-weight-bold text-uppercase"> </span>
                        <p class="my-1">
                            <a target="_blank" href="{{ route('mvr.registration.certificate', encrypt($motorVehicle->id)) }}"
                               class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                                <i class="bi bi-download mr-3"></i><u>{{ __('Download Registration Certificate') }}</u>
                            </a>
                        </p>
                    </div>
                    @endif
                    @if(!$motorVehicle->plate_number)
                        <div class="col-md-4">
                            <p class="my-1">
                                <button target="_blank" wire:click="processRegistrationPlateNumber"
                                        class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                                    <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                                       wire:target="processRegistrationPlateNumber"></i>
                                    <i class="bi bi-arrow-repeat mr-2" wire:loading.remove wire:target="processRegistrationPlateNumber"></i>Generate
                                    Plate Number
                                </button>
                            </p>
                        </div>
                    @endif

                @elseif($motorVehicle->payment_status === \App\Models\Returns\ReturnStatus::CN_GENERATING)
                    <div class="col-md-4" wire:poll.visible="refresh">
                        <span class="font-weight-bold text-uppercase text-info">Control No.</span>
                        <p class="my-1 text-info">
                            <i class="bi bi-clock-history mr-2"></i>
                            Pending
                        </p>
                    </div>
                @elseif($motorVehicle->payment_status === \App\Models\Returns\ReturnStatus::CN_GENERATION_FAILED)
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
                {{ $this->getGepgStatus($motorVehicle->latestBill->zan_trx_sts_code) }}
            </span>
                </div>
            </div>
        @else
            <div class="row py-4 alert alert-secondary bg-alt rounded-0 shadow-sm border-success">
                <div class="col-md-3">
            <span class="font-weight-bold text-uppercase">
                Total Fee Amount
            </span>
                    <p class="my-1">{{ number_format($fee->amount ?? 0, 2) }} TZS
                    </p>
                </div>
                @if(!$fee)
                    <div class="col-md-3">
                        <span class="font-weight-bold text-uppercase">Invalid Fee</span>
                        <p class="my-1 text-danger">
                            Missing Fee Configuration
                        </p>
                    </div>
                @endif
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
    @endif

</div>
