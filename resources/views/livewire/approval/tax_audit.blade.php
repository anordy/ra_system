@if (count($this->getEnabledTransitions()) >= 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Auditing Approval
        </div>
        <div class="card-body p-0 m-0">
            @include("layouts.component.messages")

            @include("livewire.approval.transitions")

            @if ($this->checkTransition("assign_officers"))
                <div class="row p-3">
                    <div class="col-lg-12 mt-2">
                        <label class="control-label h6 text-uppercase">Assign Auditors officers</label>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Team Leader</label>
                            <select class="form-control @error("teamLeader") is-invalid @enderror"
                                wire:model="teamLeader">
                                <option value='null' disabled selected>Select</option>
                                @foreach ($staffs as $row)
                                    <option value="{{ $row->id }}">{{ $row->full_name }}</option>
                                @endforeach
                            </select>
                            @error("teamLeader")
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Team Member</label>
                            <select class="form-control @error("teamMember") is-invalid @enderror"
                                wire:model="teamMember">
                                <option value='null' disabled selected>Select</option>
                                @foreach ($staffs as $row)
                                    <option value="{{ $row->id }}">{{ $row->full_name }}</option>
                                @endforeach
                            </select>
                            @error("teamMember")
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="periodFrom">Auditing Period From</label>
                            <input type="date" class="form-control @error("periodFrom") is-invalid @enderror"
                                wire:model="periodFrom">
                            @error("periodFrom")
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="periodTo">Auditing Period To</label>
                            <input type="date" class="form-control @error("periodTo") is-invalid @enderror"
                                wire:model="periodTo">
                            @error("periodTo")
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
                            @error("intension")
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
                            @error("scope")
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <div class="form-group">
                            <label for="auditingDate">Date of auditing</label>
                            <input type="date" class="form-control @error("auditingDate") is-invalid @enderror"
                                wire:model="auditingDate">
                            @error("auditingDate")
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>
            @endif
            @if ($this->checkTransition("send_notification_letter"))
                <div class="row pl-3 pr-3">
                    <p class="text-bold text-capitalize p-3 ">
                        Please Review TaxPayer to be audited and then Upload Notification Letter to be sent to taxpayer
                    </p>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Notification letter</label>
                        <input type="file" accept="pdf" class="form-control  @error("notificationLetter") is-invalid @enderror"
                            wire:model.defer="notificationLetter">
                        @error("notificationLetter")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="text-secondary small px-3">
                    <span class="font-weight-bold">
                        {{ __("Note") }}:
                    </span>
                    <span class="">
                        {{ __("Uploaded Documents must be less than 3  MB in size") }}
                    </span>
                </div>
            @endif
            @if ($this->checkTransition("audit_team_review"))
                <div class="pl-3 pr-3 card">
                    <p class="card-header ">Taxpayer Uploaded Documents</p>
                    <div class="row pt-3">
                        @if ($auditDocuments)
                            @foreach ($auditDocuments as $document)
                                <div class="col-md-3">
                                    <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                                        class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                        <a target="_blank"
                                            href="{{ route("tax_auditing.files.show", encrypt($document["path"])) }}"
                                            style="font-weight: 500;" class="ml-1">
                                            {{ $document["name"] }}
                                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    @if ($this->subject->new_audit_date)
                        <p>{{ __("Tax Payer Requested Audit Extension Until ") }}
                            <strong
                                class="text-secondary">{{ $this->subject->new_audit_date ? \Carbon\Carbon::parse($this->subject->new_audit_date)->format("F j, Y") : "" }}</strong>
                        </p>

                        <p> <strong> Extension Reasons:</strong> <br> {{ $this->subject->extension_reason }}</p>
                    @endif
                </div>
            @endif
            @if ($this->checkTransition("conduct_audit"))
                <div class="row pl-3 pr-3">
                    <div class="form-group col-lg-4">
                        <label class="control-label">Notice of discussion</label>
                        <input type="file" class="form-control  @error("entryMeeting") is-invalid @enderror"
                            wire:model.defer="entryMeeting">
                        @error("entryMeeting")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="control-label">Preliminary report</label>
                        <input type="file" class="form-control  @error("preliminaryReport") is-invalid @enderror"
                            wire:model.defer="preliminaryReport">
                        @error("preliminaryReport")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-4">
                        <label class="control-label">Working Report</label>
                        <input type="file" class="form-control  @error("workingReport") is-invalid @enderror"
                            wire:model.defer="workingReport">
                        @error("workingReport")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="text-secondary small px-3">
                    <span class="font-weight-bold">
                        {{ __("Note") }}:
                    </span>
                    <span class="">
                        {{ __("Uploaded Documents must be less than 3  MB in size") }}
                    </span>
                </div>
            @endif
            @if ($this->checkTransition("prepare_final_report"))
                <div class="row p-3">

                    <div class="form-group col-lg-6">
                        <label class="control-label">Final report</label>
                        <input type="file" class="form-control  @error("finalReport") is-invalid @enderror"
                            wire:model.defer="finalReport">
                        @error("finalReport")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Exit Minutes</label>
                        <input type="file" class="form-control  @error("exitMinutes") is-invalid @enderror"
                            wire:model.defer="exitMinutes">
                        @error("exitMinutes")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="exampleFormControlTextarea1">Has notice of asessement</label>
                        <select class="form-control @error("hasAssessment") is-invalid @enderror"
                            wire:model="hasAssessment" wire:change="hasNoticeOfAttachmentChange($event.target.value)">
                            <option value='' selected>Select</option>
                            <option value=1>Yes</option>
                            <option value=0>No</option>
                        </select>
                        @error("hasAssessment")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                @if ($hasAssessment)
                    @foreach ($principalAmounts as $taxTypeKey => $principalAmount)
                        <div class="row px-3">
                            <div class="form-group col-lg-4">
                                <label class="control-label">{{ str_replace("_", " ", $taxTypeKey) }} Principal Amount</label>
                                <input x-data x-mask:dynamic="$money($input)" type="text" class="form-control"
                                    wire:model.defer="principalAmounts.{{ $taxTypeKey }}">
                                @error("principalAmounts.{$taxTypeKey}")
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-lg-4">
                                <label class="control-label">{{ str_replace("_", " ", $taxTypeKey) }} Interest Amount</label>
                                <input x-data x-mask:dynamic="$money($input)" type="text" class="form-control"
                                    wire:model.defer="interestAmounts.{{ $taxTypeKey }}">
                                @error("interestAmounts.{$taxTypeKey}")
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-lg-4">
                                <label class="control-label">{{ str_replace("_", " ", $taxTypeKey) }} Penalty Amount</label>
                                <input x-data x-mask:dynamic="$money($input)" type="text" class="form-control"
                                    wire:model.defer="penaltyAmounts.{{ $taxTypeKey }}">
                                @error("penaltyAmounts.{$taxTypeKey}")
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endforeach

                @endif

                <div class="text-secondary small px-3">
                    <span class="font-weight-bold">
                        {{ __("Note") }}:
                    </span>
                    <span class="">
                        {{ __("Uploaded Documents must be less than 3  MB in size") }}
                    </span>
                </div>
            @endif
            <div class="row p-3">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Comments</label>
                        <textarea class="form-control @error("comments") is-invalid @enderror" wire:model.defer='comments' rows="3"></textarea>
                        @error("comments")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        @if ($this->checkTransition("start"))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'start')">
                    Initiate Approval
                </button>
            </div>
        @elseif ($this->checkTransition("assign_officers"))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'assign_officers')">
                    Assign & Forward
                </button>
            </div>
        @elseif ($this->checkTransition("send_notification_letter"))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary text-capitalize"
                    wire:click="confirmPopUpModal('approve', 'send_notification_letter')">
                    Confirm & Send to TaxPayer
                </button>
            </div>
        @elseif ($this->checkTransition("audit_team_review"))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject', 'audit_team_reject_documents')">
                    Reject Documents & Return Back
                </button>
                @if ($this->subject->new_audit_date)
                    <button type="button" class="btn btn-danger"
                        wire:click="confirmPopUpModal('reject', 'audit_team_reject_extension')">
                        Reject Extension & Return Back
                    </button>
                @endif
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'audit_team_review')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition("conduct_audit"))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'conduct_audit')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition("preliminary_report"))
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
        @elseif ($this->checkTransition("preliminary_report_review"))
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
        @elseif ($this->checkTransition("prepare_final_report"))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'prepare_final_report')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition("final_report"))
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
        @elseif ($this->checkTransition("final_report_review"))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject', 'correct_final_report_review')">
                    Reject & Return Back
                </button>

                @if ($forwardToCG)
                    <button type="button" class="btn btn-primary"
                        wire:click="confirmPopUpModal('approve', 'foward_to_commissioner')">
                        Approve & Forward
                    </button>
                @else
                    <button type="button" class="btn btn-primary"
                        wire:click="confirmPopUpModal('approve', 'final_report_review')">
                        Approve & Complete
                    </button>
                @endif
            </div>
        @elseif ($this->checkTransition("accepted"))
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
