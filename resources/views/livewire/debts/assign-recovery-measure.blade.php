<div>
    <div class="card-body">
        <h6 class="text-uppercase">Debt Details</h6>
        <hr>

        <div class="row m-2 pt-3">
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Status</span>
                <p class="my-1">{{ $debt->application_step }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Tax Type</span>
                <p class="my-1">{{ $debt->taxType->name }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Principal Amount</span>
                <p class="my-1">{{ $debt->currency }}.
                    {{ number_format($debt->principal, 2) }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Penalty</span>
                <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->penalty, 2) }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Interest</span>
                <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->interest, 2) }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Total Amount</span>
                <p class="my-1">{{ $debt->currency }}.
                    {{ number_format($debt->total_amount, 2) }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
                <p class="my-1">{{ $debt->currency }}.
                    {{ number_format($debt->outstanding_amount, 2) }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Payment Due Date</span>
                <p class="my-1">{{ $debt->curr_payment_due_date }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Recovery Measure Status</span><br>
                <span class="badge badge-primary">{{ $debt->recoveryMeasure->status ?? '' }}</span>
            </div>
        </div>
    </div>

    @if ($debt->recoveryMeasure)
        <div class="card p-0 m-0 mt-4">
            <div class="card-body mt-0 p-2">
                <h6 class="text-uppercase mt-2 ml-2">Recommended Recovery Measures</h6>
                <hr>
                <div class="row m-2 pt-3">
                    @if (count($debt->recoveryMeasure->measures ?? []) > 0)
                        @foreach ($debt->recoveryMeasure->measures as $key => $recovery_measure)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Measure Type</span>
                                <p class="my-1">{{ $key + 1 }}.
                                    {{ $recovery_measure->category->name }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="my-1">No Assigned Recovery Measures</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @livewire('approval.recovery-measure-approval-processing', ['debt' => $debt])
</div>
