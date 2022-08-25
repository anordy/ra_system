@if (count($this->getEnabledTranstions()) >= 1)
    <div class="card shadow-sm mb-2 bg-white rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Extension Request Approval
        </div>
        <div class="card-body p-4">
            @include('livewire.approval.transitions')

            @if ($this->checkTransition('debt_manager'))
                <div class="row">
                    <div class="form-group col-lg-12">
                        <label class="control-label text-uppercase font-weight-bold">Provide extension time</label>
                    </div>
                    <div class="form-group col-lg-6">
                        <div class="form-group">
                            <label>From (Current debt due date)</label>
                            <input disabled class="form-control" value="{{ \Carbon\Carbon::make($subject->debt->curr_due_date)->toFormattedDateString() }}" />
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <div class="form-group">
                            <label>Extend to</label>
                            <input min="{{ \Carbon\Carbon::make($subject->debt->curr_due_date)->toDateString() }}"
                                   max="{{ \Carbon\Carbon::make($subject->debt->curr_due_date)->addYear()->toDateString() }}"
                                   type="date" class="form-control @error('extendTo') is-invalid @enderror"
                                   wire:model="extendTo" />
                            @error('extendTo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
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
        @elseif ($this->checkTransition('debt_manager'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="approve('debt_manager')">Approve &
                    Forward</button>
            </div>
        @elseif ($this->checkTransition('crdm'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="approve('crdm')">
                    Approve & Continue
                </button>
            </div>
        @elseif ($this->checkTransition('accepted'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="reject('rejected')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="approve('accepted')">
                    Approve & Complete
                </button>
            </div>
        @endif
    </div>
@endif
