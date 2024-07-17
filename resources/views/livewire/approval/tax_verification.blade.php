@if (count($this->getEnabledTransitions()) >= 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Verification Approval
        </div>
        <div class="card-body">
            @include('livewire.approval.transitions')
            @if ($this->checkTransition('assign_officers'))
                <div class="row p-1">
                    <div class="form-group col-lg-12">
                        <label class="control-label h6 text-uppercase">Assign Compliance officers</label>
                    </div>
                    <div class="form-group col-lg-6">
                        <div class="form-group">
                            <label for="teamLeader">Team Leader</label>
                            <select class="form-control @error('teamLeader') is-invalid @enderror"
                                wire:model="teamLeader">
                                <option value='null' disabled selected>Select</option>
                                @foreach ($staffs as $row)
                                    <option value="{{ $row->id }}">{{ $row->full_name }}</option>
                                @endforeach
                            </select>
                            @error('teamLeader')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <div class="form-group">
                            <label for="teamMember">Team Member</label>
                            <select class="form-control @error('teamMember') is-invalid @enderror"
                                wire:model="teamMember">
                                <option value='null' disabled selected>Select</option>
                                @foreach ($staffs as $row)
                                    <option value="{{ $row->id }}">{{ $row->full_name }}</option>
                                @endforeach
                            </select>
                            @error('teamMember')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            @endif
            @if ($this->checkTransition('conduct_verification'))
                <div class="row p-0">

                    <div class="form-group col-lg-6">
                        <label class="control-label">Assessment Report</label>
                        <input type="file" class="form-control  @error('assessmentReport') is-invalid @enderror"
                            wire:model.lazy="assessmentReport">
                        @error('assessmentReport')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="exampleFormControlTextarea1">Has Adjusted Assessment</label>
                        <select class="form-control @error('hasAssessment') is-invalid @enderror"
                            wire:model="hasAssessment" wire:change="hasNoticeOfAttachmentChange($event.target.value)">
                            <option value='' selected>Select</option>
                            <option value=1>Yes</option>
                            <option value=0>No</option>
                        </select>
                        @error('hasAssessment')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    @if ($hasAssessment)
                        <div class="form-group col-lg-6">
                            <label class="control-label">Principal Amount</label>
                            <input type="text" class="form-control @error('principalAmount') is-invalid @enderror"
                                wire:model.defer="principalAmount">
                            @error('principalAmount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Penalty Amount</label>
                            <input type="text" class="form-control @error('penaltyAmount') is-invalid @enderror"
                                wire:model.defer="penaltyAmount">
                            @error('penaltyAmount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Interest Amount</label>
                            <input type="text" class="form-control @error('interestAmount') is-invalid @enderror"
                                wire:model.defer="interestAmount">
                            @error('interestAmount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                </div>
                    <div class="text-secondary small">
                        <span class="font-weight-bold">
                            {{ __('Note') }}:
                        </span>
                            <span class="">
                            {{ __('Uploaded Documents must be less than 3  MB in size') }}
                        </span>
                    </div>
            @endif
            @if($this->checkTransition('send_notification_to_taxpayer'))
                <div class="row p-0">
                    <div class="form-group col-lg-6">
                        <label class="control-label">Notification Letter *</label>
                        <input type="file" class="form-control  @error('notificationLetter') is-invalid @enderror"
                               wire:model.lazy="notificationLetter">
                        @error('notificationLetter')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif
            @if($this->checkTransition('officer_prepare_final_report'))
                <div class="row p-0">
                    <div class="form-group col-lg-6">
                        <label class="control-label">Final Report *</label>
                        <input type="file" class="form-control  @error('finalReport') is-invalid @enderror"
                               wire:model.lazy="finalReport">
                        @error('finalReport')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif
            @if($this->checkTransition('exit_discussion'))
                <div class="row p-0">
                    <div class="form-group col-lg-6">
                        <label class="control-label">Final Report *</label>
                        <input type="file" class="form-control  @error('finalReport') is-invalid @enderror"
                               wire:model.lazy="finalReport">
                        @error('finalReport')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Notice of Discussion *</label>
                        <input type="file" class="form-control  @error('noticeDiscussion') is-invalid @enderror"
                               wire:model.lazy="noticeDiscussion">
                        @error('noticeDiscussion')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif
            <div class="row p-0">
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
        @if ($this->checkTransition('start'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'start')">Initiate Approval</button>
            </div>
        @elseif ($this->checkTransition('assign_officers'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'assign_officers')">Assign &
                    Forward</button>
            </div>
        @elseif ($this->checkTransition('send_notification_to_taxpayer'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'send_notification_to_taxpayer')">Send Notification To Taxpayer</button>
            </div>
        @elseif ($this->checkTransition('conduct_verification'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'conduct_verification')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('manager_verification_review_report_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'manager_verification_review_report_reject')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'manager_verification_review_report_review')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('commissioner_verification_review_report_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'commissioner_verification_review_report_reject')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'commissioner_verification_review_report_review')">
                    Send To Taxpayer
                </button>
            </div>
        @elseif ($this->checkTransition('correct_reviewed_report'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'correct_reviewed_report')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'completed')">
                    Approve & Complete
                </button>
            </div>
        @elseif ($this->checkTransition('officer_prepare_final_report'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'officer_prepare_final_report')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('exit_discussion'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'exit_discussion')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('manager_final_report_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'manager_final_report_reject')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'manager_final_report_review')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('exit_discussion_correct'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'exit_discussion_correct')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'commissioner_exit_discussion_review')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('commissioner_final_report_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'commissioner_final_report_reject')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'commissioner_final_report_review')">
                    Approve & Complete
                </button>
            </div>
        @elseif ($this->checkTransition('commissioner_exit_discussion_approve'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'commissioner_exit_discussion_reject')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'commissioner_exit_discussion_approve')">
                    Approve & Complete
                </button>
            </div>
        @endif

    </div>
@endif
