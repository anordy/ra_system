@if (count($this->getEnabledTranstions()) > 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="card-header font-weight-bold">
            Approval
        </div>
        <div class="card-body">
            @include('livewire.approval.transitions')

            {{--            @if ($this->checkTransition('registration_officer_review'))--}}
            {{--                @include('livewire.approval.registration_officer_review')--}}
            {{--            @endif--}}

            <div class="row mx-1">
                <div class="col-md-12 mb-2">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Comments</label>
                        <textarea class="form-control @error('comments') is-invalid @enderror" wire:model='comments'
                                  rows="3"></textarea>

                        @error('comments')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        @if ($this->checkTransition('registration_officer_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                        wire:click="confirmPopUpModal('reject','application_filled_incorrect')">
                    <div wire:loading wire:target="reject">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    Filled Incorrect return to Applicant
                </button>

                <button wire:click="confirmPopUpModal('approve', 'registration_officer_review')"
                        wire:loading.attr="disabled"
                        class="btn btn-primary">
                    <div wire:loading wire:target="approve">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    Verify & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('registration_manager_review'))

            <div class="modal-footer p-2 m-0">
                @if(!empty($agent))
                    @if($agent->bill->status == 'paid')
                        <button type="button" class="btn btn-danger"
                                wire:click="confirmPopUpModal('reject','registration_manager_reject')">
                            <div wire:loading wire:target="reject">
                                <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            Reject & Return
                        </button>
                        <button wire:click="confirmPopUpModal('approve', 'registration_manager_review')"
                                wire:loading.attr="disabled"
                                class="btn btn-primary">
                            <div wire:loading wire:target="approve">
                                <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            Approve & Complete
                        </button>
                    @else
                        <div class="alert alert-info">Waiting for payments from Tax Consultant</div>
                    @endif
                @endif
            </div>
        @endif

    </div>
@endif
