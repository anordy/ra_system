@if (count($this->getEnabledTranstions()) >= 1)
<div class="card shadow-sm mb-2 bg-white">
    <div class="card-header font-weight-bold">
        Approval
    </div>
    <div class="card-body m-0 pb-0">
        @include('livewire.approval.transitions')

        <div class="row mt-2">
            @if ($this->checkTransition('compliance_manager_review'))
                {{-- @include('livewire.approval.registration_officer_review') --}}
            @endif
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Comments</label>
                    <textarea class="form-control @error('comments') is-invalid @enderror" wire:model='comments' rows="3"></textarea>

                    @error('comments')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    @if ($this->checkTransition('audit_manager_review'))
        <div class="modal-footer p-2 m-0">
            <button type="button" class="btn btn-danger" wire:click="reject('application_filled_incorrect')">Filled Incorrect
                return to Applicant</button>
            <button type="button" class="btn btn-primary" wire:click="approve('audit_manager_review')">Approve &
                Forward</button>
        </div>
    @elseif ($this->checkTransition('commissioner_review'))
        <div class="modal-footer p-2 m-0">
            <button type="button" class="btn btn-danger" wire:click="reject('commissioner_reject')">Reject</button>
            <button type="button" class="btn btn-primary" wire:click="approve('commissioner_review')" wire:loading.attr="disabled">
                <div wire:loading wire:target="approve('commissioner_review')">
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