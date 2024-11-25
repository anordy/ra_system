@if (count($this->getEnabledTransitions()) >= 1)
    <div class="card shadow-sm mb-2 mt-4 bg-white mx-4">
        <div class="card-header font-weight-bold">
            Approval
        </div>
        <div class="card-body">
            @include('livewire.approval.transitions')

            @if ($this->checkTransition('commissioner_general_complete'))
                @include('livewire.approval.debts.assessment_crdm_or_commissioner_review')
            @endif

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
        @if ($this->checkTransition('debt_manager_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary"
                        wire:click="confirmPopUpModal('approve','debt_manager_review')">Approve
                    & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('department_commissioner_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary"
                        wire:click="confirmPopUpModal('approve','department_commissioner_review')">Approve
                    & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('commissioner_general_complete'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                        wire:click="confirmPopUpModal('reject','commissioner_general_reject')">Reject
                </button>
                <button type="button" class="btn btn-primary"
                        wire:click="confirmPopUpModal('approve','commissioner_general_complete')">Approve &
                    Complete
                </button>
            </div>
        @endif

    </div>
@endif
