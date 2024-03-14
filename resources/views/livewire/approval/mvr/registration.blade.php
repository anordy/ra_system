@if (count($this->getEnabledTransitions()) > 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="card-header font-weight-bold">
            Approval
        </div>
        <div class="card-body">
            @include('livewire.approval.transitions')

            @if($this->checkTransition('zbs_officer_review'))

                <div class="row">
                    <div class="form-group col-lg-4">
                        <label class="control-label">Inspection Report @if (!$this->inspectionReport) * @endif</label>
                        <input type="file" class="form-control" wire:model.lazy="inspectionReport" id="inspectionReport">
                        @error('inspectionReport')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="text-secondary small">
                        <span class="font-weight-bold">
                            {{ __('Note') }}:
                        </span>
                            <span class="">
                            {{ __('Uploaded Documents must be less than 3  MB in size') }}
                        </span>
                        </div>

                    </div>

                    <div class="form-group col-lg-4">
                        <label class="control-label">Inspection Date *</label>
                        <input type="date" class="form-control" wire:model.lazy="inspectionDate" id="inspectionDate">
                        @error('inspectionDate')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-4">
                        <label class="control-label">Inspected mileage (Km) *</label>
                        <input type="number" class="form-control" wire:model.lazy="mileage" id="mileage">
                        @error('mileage')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    @if ($this->inspectionReport)
                        <div class="col-md-4">
                            <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                                    class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                <a target="_blank"
                                   href="{{ route('mvr.files', encrypt($this->inspectionReport)) }}"
                                   class="ml-1">
                                    Inspection Report
                                    <i class="bi bi-arrow-up-right-square ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

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
        @if ($this->checkTransition('zbs_officer_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                        wire:click="confirmPopUpModal('reject', 'application_filled_incorrect')">Filled
                    Incorrect
                    return to Applicant
                </button>
                <button type="button" class="btn btn-primary"
                        wire:click="confirmPopUpModal('approve', 'zbs_officer_review')">Approve
                    & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('mvr_registration_officer_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                        wire:click="confirmPopUpModal('reject', 'mvr_registration_officer_reject')">Reject &
                    Return
                </button>
                <button type="button" class="btn btn-primary"
                        wire:click="confirmPopUpModal('approve', 'mvr_registration_officer_review')">Approve
                    & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('mvr_registration_manager_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                        wire:click="confirmPopUpModal('reject', 'mvr_registration_manager_reject')">Reject &
                    Return
                </button>
                <button type="button" class="btn btn-primary"
                        wire:click="confirmPopUpModal('approve', 'mvr_registration_manager_review')">Approve &
                    Complete
                </button>
            </div>
        @endif

    </div>
@endif
