@if (count($this->getEnabledTranstions()) >= 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Investigation Approval
        </div>
        <div class="card-body">

            @include('livewire.approval.transitions')

            @if ($this->checkTransition('assign_officers'))
                <div class="row px-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label h6 text-uppercase">Assign Investigation officers</label>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="exampleFormControlTextarea1">Team Leader</label>
                        <select class="form-control @error('teamLeader') is-invalid @enderror" wire:model="teamLeader">
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
                    <div class="col-lg-6 form-group">
                        <label for="exampleFormControlTextarea1">Team Member</label>
                        <select class="form-control @error('teamMember') is-invalid @enderror" wire:model="teamMember">
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
                    <div class="col-lg-6 form-group">
                        <label for="periodFrom">Investigation Period From</label>
                        <input type="date" class="form-control @error('periodFrom') is-invalid @enderror"
                            wire:model="periodFrom">
                        @error('periodFrom')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="periodTo">Investigation Period To</label>
                        <input type="date" class="form-control @error('periodTo') is-invalid @enderror"
                            wire:model="periodTo">
                        @error('periodTo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="intension">Intension</label>
                        <textarea class="form-control" wire:model.lazy="intension" id="intension" rows="3"></textarea>
                        @error('intension')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="periodTo">Scope</label>
                        <textarea class="form-control" wire:model.lazy="scope" id="scope" rows="3"></textarea>
                        @error('scope')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            @endif

            @if ($this->checkTransition('conduct_investigation'))
                <div class="row px-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label h6 text-uppercase">Notice of Asessement</label>
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Investigation report</label>
                        <input type="file" class="form-control  @error('investigationReport') is-invalid @enderror"
                            wire:model.lazy="investigationReport">
                        @error('investigationReport')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group col-lg-6">
                        <label for="exampleFormControlTextarea1">Does Investigation contain debt</label>
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
                                wire:model.defer="principalAmount" x-data x-mask:dynamic="$money($input)" >
                            @error('principalAmount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Penalty Amount</label>
                            <input type="text" class="form-control @error('penaltyAmount') is-invalid @enderror"
                                wire:model.defer="penaltyAmount" x-data x-mask:dynamic="$money($input)" >
                            @error('penaltyAmount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Interest Amount</label>
                            <input type="text" class="form-control @error('interestAmount') is-invalid @enderror"
                                wire:model.defer="interestAmount" x-data x-mask:dynamic="$money($input)" >
                            @error('interestAmount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="control-label">Workings</label>
                            <input type="file" class="form-control  @error('workingsReport') is-invalid @enderror"
                                wire:model.lazy="workingsReport">
                            @error('workingsReport')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif


                </div>
            @endif

            <div class="row px-3">
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
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'assign_officers')">
                    Assign & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('conduct_investigation'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'conduct_investigation')">
                    Submit & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('investigation_report'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'investigation_report_correct')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'investigation_report')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('investigation_report_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="reject('investigation_report_review_reject')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'investigation_report_review')">
                    Approve & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('accepted'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="reject('rejected')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'forward_to_legal')">
                    Forward to Legal Process
                </button>   
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'accepted')">
                    Approve & Complete
                </button>
            </div>
        @endif

    </div>
@else
    <div></div>
@endif
