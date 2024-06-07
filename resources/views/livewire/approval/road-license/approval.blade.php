<div>
    @if (count($this->getEnabledTransitions()) > 1)
        @include('livewire.approval.transitions')
        <div class="card shadow-sm mb-2 bg-white">
            <div class="card-header font-weight-bold">
                Approval
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-4">
                        <label class="control-label">Inspection Date *</label>
                        <input type="date" class="form-control  @error('inspectionDate') is-invalid @enderror"
                               wire:model.defer="inspectionDate">
                        @error('inspectionDate')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="control-label">Issued Date *</label>
                        <input type="date" class="form-control  @error('issuedDate') is-invalid @enderror"
                               wire:model.defer="issuedDate">
                        @error('issuedDate')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="control-label">Expiry Date *</label>
                        <input type="date" class="form-control  @error('expiryDate') is-invalid @enderror"
                               wire:model.defer="expiryDate">
                        @error('expiryDate')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="control-label">Pass Mark *</label>
                        <input type="text" class="form-control  @error('passMark') is-invalid @enderror"
                               wire:model.defer="passMark">
                        @error('passMark')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="control-label">Passengers Number *</label>
                        <input type="text" class="form-control  @error('passengerNumber') is-invalid @enderror"
                               wire:model.defer="passengerNumber">
                        @error('passengerNumber')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="control-label">Capacity *</label>
                        <input type="text" class="form-control  @error('capacity') is-invalid @enderror"
                               wire:model.defer="capacity">
                        @error('capacity')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="control-label">Certificate of Authority Number *</label>
                        <input type="text" class="form-control  @error('certAuthNumber') is-invalid @enderror"
                               wire:model.lazy="certAuthNumber">
                        @error('certAuthNumber')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
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
            @if ($this->checkTransition('mvr_zartsa_officer_review'))
                <div class="modal-footer p-2 m-0">
                    {{--                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'application_filled_incorrect')">--}}
                    {{--                    Reject Request</button>--}}
                    <button type="button" class="btn btn-primary"
                            wire:click="confirmPopUpModal('approve', 'mvr_zartsa_officer_review')">Approve
                        & Complete
                    </button>
                </div>
            @endif
        </div>
    @endif

</div>