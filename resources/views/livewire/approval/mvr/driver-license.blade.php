@if (count($this->getEnabledTransitions()) >= 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="card-header font-weight-bold">
            Approval
        </div>
        <div class="card-body">
            @include('livewire.approval.transitions')

            @if ($this->checkTransition('police_officer_review'))
                <div class="row">
                    <div class="form-group col-lg-4">
                        <label class="control-label">Lost Report File or Letter</label>
                        <input type="file" class="form-control" wire:model.defer="lostReport">
                        @error('lostReport')
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
                </div>
            @endif

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
        @if ($this->checkTransition('zra_officer_review'))
            <div class="modal-footer p-2 m-0">
                @if(!$this->subject->duplicate_type && $this->subject->type === \App\Enum\DlFeeType::DUPLICATE)
                    <button type="button" class="btn btn-danger"
                            wire:click="confirmPopUpModal('reject', 'zra_officer_reject_to_zartsa')">Reject &
                        Return
                    </button>
                @endif
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'zra_officer_review')">Approve &
                    Complete
                </button>
            </div>
        @endif

        @if ($this->checkTransition('police_officer_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary"
                        wire:click="confirmPopUpModal('approve', 'police_officer_review')">Approve &
                    Forward
                </button>
            </div>
        @endif

        @if ($this->checkTransition('zartsa_officer_review_to_zra'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                        wire:click="confirmPopUpModal('reject', 'zartsa_officer_reject_to_police')">Reject &
                    Return
                </button>
                <button type="button" class="btn btn-primary"
                        wire:click="confirmPopUpModal('approve', 'zartsa_officer_review_to_zra')">Approve &
                    Forward
                </button>
            </div>
        @endif

    </div>
@endif
