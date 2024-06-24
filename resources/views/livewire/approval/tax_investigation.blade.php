@if (count($this->getEnabledTransitions()) >= 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Investigation Approval
        </div>
        <div class="card-body">

            @include("layouts.component.messages")

            @include("livewire.approval.transitions")

            @if ($this->checkTransition("assign_officers"))
                <div class="row px-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label h6 text-uppercase">Assign Investigation officers</label>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="exampleFormControlTextarea1">Team Leader</label>
                        <select class="form-control @error("teamLeader") is-invalid @enderror" wire:model="teamLeader">
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
                    <div class="col-lg-6 form-group">
                        <label for="exampleFormControlTextarea1">Team Member</label>
                        <button class="btn btn-primary float-right" wire:click="addSelect">+ Add member</button>
                        @foreach($teamMembers as $key => $teamMember)
                            @if($key > 0)
                                <div class="row mt-2">
                                    <div class="col">
                                        <select class="form-control @error("teamMembers.{{ $key }}") is-invalid @enderror" wire:model="teamMembers.{{ $key }}">
                                            <option value='' disabled selected>Select</option>
                                            @foreach ($staffs as $row)
                                                <option value="{{ $row->id }}">{{ $row->full_name }}</option>
                                            @endforeach
                                        </select>
                                        @error("teamMembers.{{ $key }}")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-danger" wire:click="removeSelect({{ $key }})">Remove</button>
                                    </div>
                                </div>
                            @else
                                <select class="form-control mt-2 @error("teamMembers.{{ $key }}") is-invalid @enderror" wire:model="teamMembers.{{ $key }}">
                                    <option value='' disabled selected>Select</option>
                                    @foreach ($staffs as $row)
                                        <option value="{{ $row->id }}">{{ $row->full_name }}</option>
                                    @endforeach
                                </select>
                                @error("teamMembers.{{ $key }}")
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            @endif
                        @endforeach
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="periodFrom">Investigation Period From</label>
                        <input type="date" class="form-control @error("periodFrom") is-invalid @enderror"
                            wire:model="periodFrom">
                        @error("periodFrom")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="periodTo">Investigation Period To</label>
                        <input type="date" class="form-control @error("periodTo") is-invalid @enderror"
                            wire:model="periodTo">
                        @error("periodTo")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="allegations">Allegations</label>
                        <textarea class="form-control" wire:model.lazy="allegations" id="allegations" rows="3"></textarea>
                        @error("allegations")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="periodTo">Descriptions</label>
                        <textarea class="form-control" wire:model.lazy="descriptions" id="descriptions" rows="3"></textarea>
                        @error("descriptions")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            @endif

            @if ($this->checkTransition("conduct_investigation"))
                <div class="row px-3">
                    <div class="form-group col-lg-12">
                        {{-- <label class="control-label h6 text-uppercase">Notice of Asessement</label> --}}
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Notice Of Discussion/Interview</label>
                        <input type="file" class="form-control  @error("noticeOfDiscussion") is-invalid @enderror"
                            wire:model.lazy="noticeOfDiscussion">
                        @error("noticeOfDiscussion")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Preliminary Report</label>
                        <input type="file" class="form-control  @error("preliminaryReport") is-invalid @enderror"
                            wire:model.lazy="preliminaryReport">
                        @error("preliminaryReport")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="exampleFormControlTextarea1">Does Investigation contain assessment</label>
                        <select class="form-control @error("hasAssessment") is-invalid @enderror"
                            wire:model="hasAssessment">
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
                    @if ($hasAssessment)
                        @foreach ($principalAmounts as $taxTypeKey => $principalAmount)
                            <div class="row px-3">
                                <div class="form-group col-lg-4">
                                    <label class="control-label">{{ str_replace("_", " ", $taxTypeKey) }} Principal Amount
                                        ({{ $currencies[$taxTypeKey] }})</label>
                                    <input x-data x-mask:dynamic="$money($input)" type="text" class="form-control"
                                        wire:model.defer="principalAmounts.{{ $taxTypeKey }}">
                                    @error("principalAmounts.{$taxTypeKey}")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-4">
                                    <label class="control-label">{{ str_replace("_", " ", $taxTypeKey) }} Interest Amount
                                        ({{ $currencies[$taxTypeKey] }})</label>
                                    <input x-data x-mask:dynamic="$money($input)" type="text" class="form-control"
                                        wire:model.defer="interestAmounts.{{ $taxTypeKey }}">
                                    @error("interestAmounts.{$taxTypeKey}")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-4">
                                    <label class="control-label">{{ str_replace("_", " ", $taxTypeKey) }} Penalty Amount
                                        ({{ $currencies[$taxTypeKey] }})</label>
                                    <input x-data x-mask:dynamic="$money($input)" type="text" class="form-control"
                                        wire:model.defer="penaltyAmounts.{{ $taxTypeKey }}">
                                    @error("penaltyAmounts.{$taxTypeKey}")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endforeach

                    @endif

                </div>
                <div class="text-secondary small p-3">
                    <span class="font-weight-bold">
                        {{ __("Note") }}:
                    </span>
                    <span class="">
                        {{ __("Uploaded Documents must be less than 3  MB in size in PDF or CSV Format") }}
                    </span>
                </div>
            @endif

            @if ($this->checkTransition("taxPayer_rejected_review"))
                <div class="pl-3 pr-3 card">
                    <p class="card-header">Taxpayer Responded Investigation</p>
                    <div class="row px-2 pt-2 mb-3 ">
                        <p> <strong> Taxpayer Responded Reasons:</strong> <br> {{ $this->subject->rejection_reason ?? " " }}</p>
                    </div>
                    <div class="row px-2">
                        <p> <strong> Taxpayer Supporting Documents:</strong> </p>
                    </div>
                    <div class="row">
                        @if ($investigationDocuments)
                            @foreach ($investigationDocuments as $document)
                                <div class="col-md-3">
                                    <div class="p-2 mb-3 d-flex rounded-sm align-items-center highlighted-file-box">
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

                </div>
            @endif

            @if ($this->checkTransition("final_report"))
                <div class="row px-3">
                    <div class="form-group col-lg-6">
                        <label class="control-label">Final Report</label>
                        <input type="file" class="form-control  @error("finalReport") is-invalid @enderror"
                            wire:model.lazy="finalReport">
                        @error("finalReport")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    @if ($hasAssessment)
                        <div class="form-group col-lg-6">
                            <label class="control-label">Working Of Final Report</label>
                            <input type="file" class="form-control  @error("workingReport") is-invalid @enderror"
                                wire:model.lazy="workingReport">
                            @error("workingReport")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                </div>
                <div class="text-secondary small p-3">
                    <span class="font-weight-bold">
                        {{ __("Note") }}:
                    </span>
                    <span class="">
                        {{ __("Uploaded Documents must be less than 3  MB in size in PDF or CSV Format") }}
                    </span>
                </div>
            @endif

            @if ($this->checkTransition("extension_approved"))
                <div class="row px-3">
                    <div class="col-lg-6 form-group">
                        <label for="periodFrom">Suggested Extension Date</label>
                        <input type="date" class="form-control @error("extensionDate") is-invalid @enderror"
                            wire:model="extensionDate">
                        @error("extensionDate")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="periodFrom">Extension Reason</label>
                        <input type="text" disabled class="form-control"
                            wire:model="extensionReason">
                    </div>
                </div>
            @endif

            <div class="row px-3">
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
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'assign_officers')">
                    Assign & Forward
                </button>
            </div>
        @elseif ($this->checkTransition("conduct_investigation"))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'conduct_investigation')">
                    Submit & Forward
                </button>
            </div>
        @elseif ($this->checkTransition("taxPayer_rejected_review"))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'taxPayer_rejected_review')">
                    Submit & Forward
                </button>
            </div>
        @elseif ($this->checkTransition("investigation_report_review"))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="reject('investigation_report_reject')">
                    Reject & Return Back
                </button>

                @if ($subject->was_rejected)
                    <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'prepare_final_report')">
                        Accept & Forward
                    </button>
                @else
                    <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'investigation_report_review')">
                        Approve & Forward
                    </button>
                @endif
            </div>
        @elseif ($this->checkTransition("final_report"))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'final_report')">
                    Submit & Forward
                </button>
            </div>
        @elseif ($this->checkTransition("final_report_review"))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="reject('final_report_reject')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'final_report_review')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition("accepted"))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="reject('rejected')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'accepted')">
                    Approve & Complete
                </button>
            </div>
        @elseif ($this->checkTransition("extension_approved"))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                        wire:click="confirmPopUpModal('rejectExtension', 'extension_approved')">
                    Reject extension
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'extension_approved')">
                    Agree/Amend to Extension Date
                </button>
            </div>
        @endif

    </div>
@endif