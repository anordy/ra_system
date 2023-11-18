@if (count($this->getEnabledTransitions()) >= 1)
    <div class="card shadow-sm mb-2 bg-white rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Verification Approval
        </div>
        <div class="card-body p-4">
            @include('livewire.approval.transitions')

            @if ($this->checkTransition('assign_officers'))
                <div class="row">
                    <div class="form-group col-lg-12">
                        <label class="control-label h6 text-uppercase">Assign Compliance officers</label>
                    </div>
                    <div class="form-group col-lg-6">
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
                    <div class="form-group col-lg-6">
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
                </div>
            @endif
            @if ($this->checkTransition('verification_results'))
                <div class="row">
                    <div class="form-group col-lg-12">
                        <label class="control-label font-weight-bold text-uppercase">Tax Claim Verification Assessment</label>
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Assessment Report</label>
                        <input type="file" class="form-control-file border p-1  @error('assessmentReport') is-invalid @enderror"
                            wire:model.lazy="assessmentReport">
                        @error('assessmentReport')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif
            @if($this->checkTransition('method_of_payment'))
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Payment Type</label>
                        <select wire:model="paymentType" class="form-control">
                            <option></option>
                            <option value="cash">Cash</option>
                            <option value="installment">Installment</option>
                            <option value="full">Full Payment</option>
                        </select>
                    </div>

                    @if($paymentType === 'installment')
                        <div class="col-md-4 form-group">
                            <label>Installment Phases</label>
                            <input class="form-control @error('installmentCount') is-invalid @enderror" wire:model="installmentCount" placeholder="E.g. 2 phases" max="12" type="number">
                            @error('installmentCount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label>Payment per month</label>
                            <input class="form-control" disabled value="{{ number_format($this->calcMoney(), 2) }} {{ $this->subject->currency }}">
                        </div>
                    @endif
                </div>
            @endif
            <div class="row">
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
        @elseif ($this->checkTransition('verification_results'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'verification_results')">
                    Approve & Continue
                </button>
            </div>
        @elseif ($this->checkTransition('method_of_payment'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'method_of_payment')">
                    Approve & Continue
                </button>
            </div>
        @elseif ($this->checkTransition('accepted'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'rejected')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'accepted')">
                    Approve & Complete
                </button>
            </div>
        @elseif ($this->checkTransition('correct_reviewed_report'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'correct_reviewed_report')">
                    Reject & Return Back
                </button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'accepted')">
                    Approve & Complete
                </button>
            </div>
        @endif
    </div>
@endif
