@if (count($this->getEnabledTransitions()) >= 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="row m-2 pt-3">
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">New Payment Due Date</span>
                <div class="my-1">
                    <input type="date" class="form-control @error('new_payment_due_date') is-invalid @enderror" wire:model.defer="new_payment_due_date" min="{{ \Carbon\Carbon::parse($paymentExtension->extension_from)->format('Y-m-d') }}">
                    @error('new_payment_due_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-header font-weight-bold">
            Approval
        </div>
        <div class="card-body m-0 pb-0">
            @include('livewire.approval.transitions')
            <div class="row mt-2">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Comments</label>
                        <textarea class="form-control @error('comments') is-invalid @enderror" wire:model.defer='comments' rows="3"></textarea>

                        @error('comments')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        @if ($this->checkTransition('commissioner_approve'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'commissioner_reject')">Filed Incorrect
                    return to Applicant</button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve','commissioner_approve')" wire:loading.attr="disabled">
                        <div wire:loading>
                            <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        Approve & Complete
                </button>
            </div>
        @endif
    </div>
@else
<div></div>
@endif
