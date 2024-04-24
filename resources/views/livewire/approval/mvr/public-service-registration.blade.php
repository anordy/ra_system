@if (count($this->getEnabledTransitions()) > 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="card-header font-weight-bold">
            Approval
        </div>
        <div class="card-body">
            @if($this->checkTransition('public_service_registration_manager_review'))
                @if($subject->payment)
                    <div class="row m-2 pt-3">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Third Schedule</span>
                            <p class="my-1">{{ $subject->payment->paymentCategory->name  }} - TZS {{ number_format($subject->payment->paymentCategory->turnover_tax, 2)  }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Payment Months</span>
                            <p class="my-1">{{ $subject->payment->payment_months ?? 'N/A'  }} Months</p>
                        </div>
                    </div>
                @endif
            @endif

            @include('livewire.approval.transitions')

            @if($this->checkTransition('public_service_registration_officer_review'))
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Third Schedule Turnover *</label>
                            <select class="form-control @error('psPaymentCategoryId') is-invalid @enderror"
                                     wire:model.defer="psPaymentCategoryId">
                                <option value="null" disabled selected>Select</option>
                                @foreach ($psPaymentCategories as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }} - TZS {{ number_format($row->turnover_tax, 2)  }}</option>
                                @endforeach
                            </select>
                            @error('psPaymentCategoryId')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Payment Months *</label>
                            <select class="form-control @error('psPaymentMonthId') is-invalid @enderror"
                                    wire:model.defer="psPaymentMonthId">
                                <option value="null" disabled selected>Select</option>
                                @foreach ($psPaymentMonths as $row)
                                    <option value="{{ $row->id }}">{{ $row->value }} Months</option>
                                @endforeach
                            </select>
                            @error('psPaymentMonthId')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
            @endif

            <div class="row m">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Comments</label>
                        <textarea class="form-control @error('comments') is-invalid @enderror"
                                  wire:model.defer='comments' rows="3"></textarea>

                        @error('comments')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        @if ($this->checkTransition('public_service_registration_officer_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                        wire:click="confirmPopUpModal('reject', 'application_filled_incorrect')">Filed
                    Incorrect
                    return to Applicant
                </button>
                <button type="button" class="btn btn-primary"
                        wire:click="confirmPopUpModal('approve', 'public_service_registration_officer_review')">Approve
                    & Forward
                </button>
            </div>
        @elseif ($this->checkTransition('public_service_registration_manager_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                        wire:click="confirmPopUpModal('reject', 'public_service_registration_manager_reject')">Reject &
                    Return
                </button>
                <button type="button" class="btn btn-primary"
                        wire:click="confirmPopUpModal('approve', 'public_service_registration_manager_review')">Approve
                    &
                    Complete
                </button>
            </div>
        @endif

    </div>
@endif
