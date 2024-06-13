@if (count($this->getEnabledTransitions()) > 1)
    <div class="card shadow-sm mb-2 bg-white border-0 shadow-none">
        <div class="card-header font-weight-bold">
            Transport Service De-registration Approval
        </div>
        <div class="card-body">
            @include('livewire.approval.transitions')


            <div class="row m">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Comments</label>
                        <textarea class="form-control @error('comments') is-invalid @enderror"
                                  wire:model.defer='comments' rows="3"></textarea>

                        @error('comments')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        @if ($this->checkTransition('public_service_registration_officer_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                        wire:click="confirmPopUpModal('reject', 'application_filled_incorrect')">Filled
                    Incorrect
                    return to Applicant
                </button>
                <button type="button" class="btn btn-primary"
                        wire:click="confirmPopUpModal('approve', 'public_service_registration_officer_review')">Approve
                    & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('public_service_registration_manager_review'))
            <adiv class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                        wire:click="confirmPopUpModal('reject', 'public_service_registration_manager_reject')">Reject &
                    Return
                </button>
                <button type="button" class="btn btn-primary"
                        wire:click="confirmPopUpModal('approve', 'public_service_registration_manager_review')">Approve
                    &
                    Complete
                </button>
            </adiv>
        @endif

    </div>
@endif
