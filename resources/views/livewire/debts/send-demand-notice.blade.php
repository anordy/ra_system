<div class="card">

    <div class="card-body">
        <div>
            <h6 class="text-uppercase mt-2 ml-2">Overdue Debt Details</h6>
            <hr>
        </div>

        <div class="row m-2 pt-3">
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Debt Status</span>
                <p class="my-1">{{ $debt->app_step }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Tax Type</span>
                <p class="my-1">{{ $debt->taxType->name }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Principal Amount</span>
                <p class="my-1">{{ $debt->currency }}.
                    {{ number_format($debt->principal_amount, 2) }}</p>
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
                    {{ number_format($debt->original_total_amount, 2) }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
                <p class="my-1">{{ $debt->currency }}.
                    {{ number_format($debt->outstanding_amount, 2) }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Payment Due Date</span>
                <p class="my-1">{{ $debt->curr_due_date }}</p>
            </div>
        </div>
    </div>

    @if (count($debt->recoveryMeasures))
        <div class="card p-0 m-0 mt-4">
            <div class="card-body mt-0 p-2">
                <h6 class="text-uppercase mt-2 ml-2">Recommended Recovery Measures</h6>
                <hr>
                <div class="row m-2 pt-3">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Status</span><br>
                        <span class="badge badge-primary">{{ $debt->recovery_measure_status }}</span>
                    </div>

                    @foreach ($debt->recoveryMeasures as $key => $recovery_measure)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Measure Type</span>
                            <p class="my-1">{{ $key + 1 }}. {{ $recovery_measure->category->name }}</p>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    @endif

    {{-- <livewire:debt.demand-notice.demand-notice-table debtId="{{ $debt->id }}" /> --}}


    {{-- <div class="modal-footer p-2 m-0">
        <a href="{{ route('debts.debt.showOverdue', encrypt($debt->id)) }}" type="button" class="btn btn-danger">Cancel</a>
        <button type="button" class="btn btn-primary"
            wire:click="send()">Send Demand Notice</button>
    </div> --}}


</div>

</div>

</div>
