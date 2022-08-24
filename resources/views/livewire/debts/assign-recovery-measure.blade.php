<div>
    <div class="card">
        <div class="card-body">
            <h6 class="text-uppercase mt-2 ml-2">Debt Details</h6>
            <hr>
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Status</span>
                    <p class="my-1">{{ $debt->app_step }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Type</span>
                    <p class="my-1">{{ $debt->taxType->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Principal Amount</span>
                    <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->principal_amount, 2) }}</p>
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
                    <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->original_total_amount, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
                    <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->outstanding_amount, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Payment Due Date</span>
                    <p class="my-1">{{ $debt->curr_due_date }}</p>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.approval.transitions')


    <div class="card">

        <div class="card-body">
            <h6 class="text-uppercase mt-2 ml-2">Assign Recovery Measures</h6>
            <hr>

            <div class="row m-2 pt-3">
                <div class="col-md-12 form-group">
                    <label>Select Recovery Measure: *</label>
                    <select wire:model="recovery_measures" multiple size="6" class="form-control">
                        <option></option>
                        @foreach ($recovery_measure_categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('recovery_measures')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Comments</label>
                        <textarea class="form-control" wire:model='comments' rows="3"></textarea>
                    </div>
                </div>
        
            </div>

            @if ($this->checkTransition('crdm_assign'))
                <div class="modal-footer p-2 m-0">
                    <a href="{{ route('debts.debt.index') }}" class="btn btn-danger mr-2">Cancel</a>
                    <button type="button" class="btn btn-primary" wire:click="approve('crdm_assign')" wire:loading.attr="disabled">
                        <div wire:loading wire:target="approve">
                            <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        Submit
                    </button>
                </div>
            @elseif ($this->checkTransition('commissioner_review'))
                <div class="modal-footer p-2 m-0">
                    <button type="button" class="btn btn-danger"
                        wire:click="reject('assignment_incorrect')">Reject & Return</button>
                    <button type="button" class="btn btn-primary"
                        wire:click="approve('commissioner_review')">Approve & Forward</button>
                </div>
            @endif
        </div>

    </div>

</div>
