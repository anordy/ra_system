@if (count($this->getEnabledTranstions()) >= 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="card-header font-weight-bold">
            Tax Verification Approval
        </div>
        <div class="card-body">
            @include('livewire.approval.transitions')

            @if ($this->checkTransition('assign_officers'))
                <div class="row px-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label h6 text-uppercase">Assign Compliance officers</label>
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Team Leader</label>
                        <input type="text" class="form-control" wire:model.lazy="name" id="name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Team Member</label>
                        <input type="text" class="form-control" wire:model.lazy="name" id="name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif
            @if ($this->checkTransition('conduct_verification'))
                <div class="row px-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label h6 text-uppercase">Notice of Asessement</label>
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Principal Amount</label>
                        <input type="text" class="form-control" wire:model.lazy="name" id="name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Interest Amount</label>
                        <input type="text" class="form-control" wire:model.lazy="name" id="name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Penalty Amount</label>
                        <input type="text" class="form-control" wire:model.lazy="name" id="name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="control-label">Assessment Report</label>
                        <input type="file" class="form-control" wire:model.lazy="name" id="name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            @endif
            <div class="row px-3">
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
        @if ($this->checkTransition('start'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="approve('start')">Initiate Approval</button>
            </div>
        @elseif ($this->checkTransition('assign_officers'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="approve('assign_officers')">Assign &
                    Forward</button>
            </div>
        @elseif ($this->checkTransition('conduct_verification'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="approve('conduct_verification')">
                    Approve & Complete
                </button>
            </div>
        @elseif ($this->checkTransition('verification_review_report'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="reject('correct_verification_report')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="approve('verification_review_report')">
                    Approve & Complete
                </button>
            </div>
        @elseif ($this->checkTransition('correct_reviewed_report'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="reject('correct_reviewed_report')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="approve('accepted')">
                    Approve & Complete
                </button>
            </div>
        @endif

    </div>
@endif
