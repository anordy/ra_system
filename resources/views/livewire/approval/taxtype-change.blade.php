<div class="card shadow-sm mb-2 bg-white">
    <div class="card-header font-weight-bold">
        Approval
    </div>
    <div class="card-body m-0 pb-0">
        @include('livewire.approval.transitions')

        <div class="row mt-2">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Comments</label>
                    <textarea class="form-control" wire:model='comments' rows="3"></textarea>
                </div>
            </div>
        </div>
    </div>
    @if ($this->checkTransition('registration_manager_review'))
        <div class="modal-footer p-2 m-0">
            <button type="button" class="btn btn-danger" wire:click="reject('registration_manager_review')">Reject</button>
            <button type="button" class="btn btn-primary" wire:click="approve('registration_manager_review')">Approve &
                Forward to Auditor</button>
        </div>
    @endif

</div>
