@if (count($this->getEnabledTransitions()) > 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="card-header font-weight-bold">
            Approval
        </div>
        <div class="card-body">
            @include('livewire.approval.transitions')
            {{-- @if ($this->checkTransition('objection_manager_review'))
                @include('livewire.approval.assesments.objection_manager_review')
            @elseif ($this->checkTransition('commisioner_review'))
                @include('livewire.approval.assesments.objection_commisioner_review')
            @endif --}}
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


        @if ($this->checkTransition('crdm_review'))
            <div class="modal-footer p-2 m-0">
                <button wire:click="confirmPopUpModal('reject', 'crdm_reject')" class="btn btn-danger px-3 ml-2" type="button"
                    wire:loading.attr="disabled">
                    <i class="bi bi-x-square mr-2" wire:loading.remove
                        wire:target="reject('crdm_reject')"></i>
                    <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                        wire:target="reject('crdm_reject')"></i>
                    Reject
                </button>
                <button wire:click="confirmPopUpModal('approve', 'crdm_review')" class="btn btn-primary px-3 ml-2" type="button"
                    wire:loading.attr="disabled">
                    <i class="bi bi-arrow-return-right mr-2" wire:loading.remove
                        wire:target="approve('crdm_review')"></i>
                    <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                        wire:target="approve('crdm_review')"></i>
                    Approve & Complete
                </button>
            </div>
        @endif

    </div>
@endif
