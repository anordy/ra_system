<div>
    @if (count($this->getEnabledTransitions()) >= 1)
        <div class="card shadow-sm mt-2 bg-white">
            <div class="card-header font-weight-bold">
                Approval
            </div>
            <div class="card-body">
                @include('livewire.approval.transitions')

                <div class="row m">
                    @if ($this->checkTransition('zartsa_officer_correct'))
                        <div class="col-md-12 mb-3 form-group">
                            <label>Reason *</label>
                            <textarea class="form-control @error("reason") is-invalid @enderror"
                                      wire:model.defer='reason' rows="4"></textarea>
                            @error("reason")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3 form-group">
                            <label>Evidence File (Optional)</label>
                            <input type="file" wire:model.defer="evidenceFile" class="form-control">
                            @error("evidenceFile")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    @endif
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
            @if ($this->checkTransition('zartsa_officer_correct'))
                <div class="modal-footer p-2 m-0">
                    <button type="button" class="btn btn-primary"
                            wire:click="confirmPopUpModal('approve', 'zartsa_officer_correct')">
                        Submit & Forward
                    </button>
                </div>
            @elseif($this->checkTransition('zartsa_manager_review'))
                <div class="modal-footer p-2 m-0">
                    <button type="button" class="btn btn-danger"
                            wire:click="confirmPopUpModal('reject', 'zartsa_manager_reject')">
                        Return for Correction
                    </button>
                    <button type="button" class="btn btn-primary"
                            wire:click="confirmPopUpModal('approve', 'zartsa_manager_review')">
                        Approve & Complete
                    </button>
                </div>
            @endif
        </div>
    @endif

</div>