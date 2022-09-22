@if (count($this->getEnabledTranstions()) >= 1)
    <div class="card shadow-sm mb-2 mt-4 bg-white mx-4">
        <div class="card-header font-weight-bold">
            Approval
        </div>
        <div class="card-body">
            @include('livewire.approval.transitions')

            @if ($this->checkTransition('crdm_review'))
                @if (!$forwardToCommisioner)
                    @include('livewire.approval.debts.assessment_crdm_or_commissioner_review')
                @endif
            @endif

            @if ($this->checkTransition('commissioner_complete'))
                @include('livewire.approval.debts.assessment_crdm_or_commissioner_review')
            @endif

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
        @if ($this->checkTransition('debt_manager_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="approve('debt_manager_review')">Approve
                    & Forward</button>
            </div>
        @elseif ($this->checkTransition('crdm_review'))
            <div class="modal-footer p-2 m-0">
                @if ($forwardToCommisioner)
                <button button type="button" class="btn btn-primary" wire:click="approve('crdm_review')">Approve
                    & Forward</button>
                @else
                    <button type="button" class="btn btn-danger" wire:click="reject('crdm_reject')">Reject</button>
                    <button type="button" class="btn btn-primary" wire:click="approve('crdm_complete')">Approve & Complete</button>
                @endif
            </div>
        @elseif ($this->checkTransition('commissioner_complete'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="reject('commissioner_reject')">Reject</button>
                <button type="button" class="btn btn-primary" wire:click="approve('commissioner_complete')">Approve &
                    Complete</button>
            </div>
        @endif

    </div>
@endif
