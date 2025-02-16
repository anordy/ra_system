@if (count($this->getEnabledTransitions()) > 1)
    <div class="card shadow-sm mt-2 bg-white">
        <div class="card-header font-weight-bold">
            Approval
        </div>
        <div class="card-body">
            @include('livewire.approval.transitions')

            <div class="row m">
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
        @if ($this->checkTransition('mvr_registration_officer_review'))
            <div class="modal-footer p-2 m-0">
                @if(!$this->subject->extended_date)
                    <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'application_filled_incorrect')">
                        Return for Correction
                    </button>
                @endif
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'mvr_registration_officer_review')">
                    Approve & Forward
                </button>
            </div>
        @elseif($this->checkTransition('mvr_registration_manager_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'mvr_registration_manager_reject')">
                    Reject & Return
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'mvr_registration_manager_review')">
                    Approve
                </button>
            </div>
        @endif
    </div>
@endif
