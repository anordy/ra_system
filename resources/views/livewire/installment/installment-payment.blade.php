<div class="row py-4 alert alert-secondary bg-alt rounded-0 shadow-sm border-success">
    @if($installment->status == \App\Enum\InstallmentStatus::ACTIVE)
        @if($installment->getNextPaymentDate())
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase">Payment Due Date</span>
                <p class="my-1">{{ $installment->getNextPaymentDate()->toDayDateTimeString() }}</p>
            </div>
        @endif
        @if($activeItem)
            <div class="col-md-2">
                <span class="font-weight-bold text-uppercase">Amount</span>
                <p class="my-1">{{ $activeItem->currency }}. {{ number_format($activeItem->amount, 2) }}</p>
            </div>
            <div class="col-md-2">
                <span class="font-weight-bold text-uppercase">Control No</span>
                <p class="my-1">{{ $activeItem->bill->control_number }}</p>
            </div>
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase">Status</span>
                <p class="my-1">{{ ucwords(str_replace('-', ' ', $activeItem->status)) }}</p>
            </div>
            <div class="col-md-2 d-flex justify-content-end">
                <span class="font-weight-bold text-uppercase"> </span>
                <p class="my-1">
                    <a target="_blank" href="{{ route('bill.invoice', encrypt($activeItem->bill->id)) }}" class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                        <i class="bi bi-download mr-3"></i><u>Download Bill</u>
                    </a>
                </p>
            </div>
        @else
            <div class="col-md-4">
                <span class="font-weight-bold text-uppercase"> </span>
                <p class="my-1">
                    <button wire:click="generateItem" class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                        <i class="bi bi-plus-circle-dotted mr-2" wire:loading.remove></i>
                        <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading></i>
                        Generate Control No
                    </button>
                </p>
            </div>
        @endif
    @elseif($installment->status == \App\Enum\InstallmentStatus::CANCELLED)
        <div class="col-md-3">
            <span class="font-weight-bold text-uppercase">Installment Status</span>
            <p class="my-1 text-danger text-uppercase">{{ $installment->status }}</p>
        </div>
        <div class="col-md-3">
            <span class="font-weight-bold text-uppercase">Cancellation Reason</span>
            <p class="my-1">{{ $installment->cancellation_reason }}</p>
        </div>
    @endif
</div>