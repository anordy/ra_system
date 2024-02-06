@if (count($this->getEnabledTransitions()) >= 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Auditing Approval
        </div>
        <div class="card-body p-0 m-0">
            @include('layouts.component.messages')

            @include('livewire.approval.transitions')

            @if ($this->checkTransition('assign_officers'))
                <div class="row p-3">
                    <div class="col-lg-12 mt-2">
                        <label class="control-label h6 text-uppercase">Assign Auditors officers</label>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Team Leader</label>
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
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Team Member</label>
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
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="periodFrom">Auditing Period From</label>
                            <input type="date" class="form-control @error('periodFrom') is-invalid @enderror"
                                wire:model="periodFrom">
                            @error('periodFrom')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="periodTo">Auditing Period To</label>
                            <input type="date" class="form-control @error('periodTo') is-invalid @enderror"
                                wire:model="periodTo">
                            @error('periodTo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="intension">Intension</label>
                            <textarea class="form-control" wire:model.defer="intension" id="intension" rows="3"></textarea>
                            @error('intension')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="periodTo">Scope</label>
                            <textarea class="form-control" wire:model.defer="scope" id="scope" rows="3"></textarea>
                            @error('scope')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <div class="form-group">
                            <label for="auditingDate">Date of auditing</label>
                            <input type="date" class="form-control @error('auditingDate') is-invalid @enderror"
                                wire:model="auditingDate">
                            @error('auditingDate')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>
            @endif
            @if ($this->checkTransition('conduct_audit'))
                <div class="row pl-3 pr-3">
                    <div class="form-group col-lg-6">
                        <label class="control-label">Preliminary report</label>
                        <input type="file" class="form-control  @error('preliminaryReport') is-invalid @enderror"
                            wire:model.defer="preliminaryReport">
                        @error('preliminaryReport')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="control-label">Working</label>
                        <input type="file" class="form-control  @error('workingReport') is-invalid @enderror"
                            wire:model.defer="workingReport">
                        @error('workingReport')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif
            @if ($this->checkTransition('prepare_final_report'))
                <div class="row p-3">

                    <div class="form-group col-lg-6">
                        <label class="control-label">Final report</label>
                        <input type="file" class="form-control  @error('finalReport') is-invalid @enderror"
                            wire:model.defer="finalReport">
                        @error('finalReport')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="control-label">Exit Minutes</label>
                        <input type="file" class="form-control  @error('exitMinutes') is-invalid @enderror"
                            wire:model.defer="exitMinutes">
                        @error('exitMinutes')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="exampleFormControlTextarea1">Has notice of asessement</label>
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
                            <input x-data x-mask:dynamic="$money($input)" type="text"
                                class="form-control @error('principalAmount') is-invalid @enderror"
                                wire:model.defer="principalAmount">
                            @error('principalAmount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Interest Amount</label>
                            <input x-data x-mask:dynamic="$money($input)" type="text"
                                class="form-control @error('interestAmount') is-invalid @enderror"
                                wire:model.defer="interestAmount">
                            @error('interestAmount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Penalty Amount</label>
                            <input x-data x-mask:dynamic="$money($input)" type="text"
                                class="form-control @error('penaltyAmount') is-invalid @enderror"
                                wire:model.defer="penaltyAmount">
                            @error('penaltyAmount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                </div>
            @endif
            <div class="row p-3">
                <div class="col-md-12 ">
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
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'start')">
                    Initiate Approval
                </button>
            </div>
        @elseif ($this->checkTransition('assign_officers'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'assign_officers')">
                    Assign & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('conduct_audit'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'conduct_audit')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('preliminary_report'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject', 'correct_preliminary_report')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'preliminary_report')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('preliminary_report_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject', 'correct_preliminary_report_review')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'preliminary_report_review')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('prepare_final_report'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'prepare_final_report')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('final_report'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject', 'correct_final_report')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'final_report')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('final_report_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject', 'correct_final_report_review')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'final_report_review')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('accepted'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject', 'rejected')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'accepted')">
                    Approve & Complete
                </button>
            </div>
        @endif

    </div>
@endif
