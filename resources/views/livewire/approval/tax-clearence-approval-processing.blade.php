@if (count($this->getEnabledTranstions()) > 1)
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
                        <textarea class="form-control @error('comments') is-invalid @enderror" wire:model='comments' rows="3"></textarea>

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
                <button type="button" class="btn btn-danger" wire:click="reject('crdm_reject')">Reject
                    </button>
                <button type="button" class="btn btn-primary" wire:click="approve('crdm_review')">Approve &
                    Complete</button>
            </div>
        @endif

    </div>
@endif
