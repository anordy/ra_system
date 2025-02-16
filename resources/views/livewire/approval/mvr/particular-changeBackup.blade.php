@if (count($this->getEnabledTransitions()) > 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="card-header font-weight-bold">
            Approval
        </div>
        <div class="card-body">
            @include('livewire.approval.transitions')

            @if ($this->checkTransition('mvr_zartsa_review'))
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label class="control-label">Approval Report</label>
                                        <input type="file"
                                            class="form-control  @error('approvalReport') is-invalid @enderror"
                                            wire:model.lazy="approvalReport">
                                        @error('approvalReport')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="text-secondary small">
                                    <span class="font-weight-bold">
                                        {{ __('Note') }}:
                                    </span>
                                    <span class="">
                                        {{ __('Uploaded Documents must be less than 3  MB in size') }}
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label class="control-label">Color</label>
                        <input type="text" class="form-control" wire:model.defer="color" disabled>
                        @error('color')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label class="control-label">Body Style</label>
                        <input type="text" class="form-control" wire:model.defer="bodyStyle" disabled>
                        @error('bodyStyle')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label class="control-label">Engine Number</label>
                        <input type="text" class="form-control" wire:model.defer="engineNo" disabled>
                        @error('engineNo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-3">
                        <label class="control-label">Chassis Number</label>
                        <input type="text" class="form-control" wire:model.defer="chassisNo" disabled>
                        @error('chassisNo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif

            <div class="row">
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

        @if ($this->checkTransition('mvr_zartsa_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject', 'application_rejected')">
                    Reject Application</button>
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'mvr_zartsa_review')">Approve
                    & Forward</button>
            </div>
        @elseif ($this->checkTransition('mvr_registration_officer_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject', 'mvr_registration_officer_reject')">Reject &
                    Return</button>
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'mvr_registration_officer_review')">Approve &
                    Forward</button>
            </div>
        @elseif ($this->checkTransition('mvr_registration_manager_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject', 'mvr_registration_manager_reject')">Reject &
                    Return</button>
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'mvr_registration_manager_review')">Approve &
                    Complete</button>
            </div>
        @endif

    </div>
@endif
