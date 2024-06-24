@if (count($this->getEnabledTransitions()) >= 1)
<div class="card shadow-sm mb-2 bg-white">
    <div class="card-header font-weight-bold">
        Approval
    </div>
    <div class="card-body m-0 pb-0">
        @include('livewire.approval.transitions')

        <div class="row mt-2">
            @if ($this->checkTransition('compliance_manager_review'))
                {{-- @include('livewire.approval.registration_officer_review') --}}
            @endif
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
            {{-- @if ($this->checkTransition('compliance_manager_review'))
                <div class="col-md-6 mb-3">
                    <label>Assign To Role</label>
                    <select wire:model="officer_id"
                        class="form-control {{ $errors->has('officer_id') ? 'is-invalid' : '' }}">
                        <option></option>
                        @foreach ($officers as $officer)
                            <option value="{{ $officer->id }}">{{ $officer->fullname }}</option>
                        @endforeach
                    </select>
                    @error('officer_id')
                        <div class="invalid-feedback">
                            {{ $errors->first('officer_id') }}
                        </div>
                    @enderror
                </div>
            @endif --}}
        </div>
    </div>
    @if ($this->checkTransition('compliance_manager_review'))
        <div class="modal-footer p-2 m-0">
            <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'application_filled_incorrect')">Filed Incorrect
                return to Applicant</button>
            <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve','compliance_manager_review')">Approve &
                Forward</button>
        </div>
    @elseif ($this->checkTransition('compliance_officer_review'))
        <div class="modal-footer p-2 m-0">
            <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'compliance_officer_reject')">Reject & Return</button>
            <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve','compliance_officer_review')" wire:loading.attr="disabled">
                    <div wire:loading wire:target="approve('compliance_officer_review')">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    Approve & Complete
            </button>
        </div>
    @endif

</div>
@else
<div></div>
@endif
