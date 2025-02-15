@if (count($this->getEnabledTransitions()) > 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="card-header font-weight-bold">
            Approval
        </div>
        <div class="card-body">
            @include('livewire.approval.transitions')

            @if ($this->checkTransition('police_officer_review'))
            @include('livewire.approval.mvr.reorder.police_review')
            @endif

            @if ($this->checkTransition('zartsa_officer_review_to_zra'))
                @include('livewire.approval.mvr.reorder.zartsa_review')
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
        @if ($this->checkTransition('police_officer_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'police_officer_reject')">
                    Reject Application</button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'police_officer_review')">Approve
                    & Forward</button>
            </div>
        @elseif ($this->checkTransition('zartsa_officer_review_to_zra'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'zartsa_officer_reject_to_police')">
                    Reject Application</button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'zartsa_officer_review_to_zra')">Approve
                    & Forward</button>
            </div>
        {{-- @elseif  ($this->checkTransition('application_returned_for_distorted'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'application_returned_for_distorted')">Reject &
                    Return</button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'zra_officer_review')">Approve &
                    Forward</button>
            </div> --}}
            @elseif  ($this->checkTransition('zra_officer_review_lost'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'zra_officer_reject_to_zartsa')">Reject &
                    Return</button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'zra_officer_review_lost')">Approve &
                    Forward</button>
            </div>
        @elseif  ($this->checkTransition('zra_officer_review_distorted'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'application_returned_for_distorted')">Reject &
                    Return</button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'zra_officer_review_distorted')">Approve &
                    Complete</button>
            </div>
        @endif

    </div>
@endif
