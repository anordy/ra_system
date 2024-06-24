@if (count($this->getEnabledTransitions()) >= 1)
    <div class="card shadow-sm mb-2 bg-white">
        <div class="card-header font-weight-bold">
            Approval
        </div>
        <div class="card-body m-0 pb-0">
            @include('livewire.approval.transitions')

            @if ($this->checkTransition('registration_manager_review'))
                @if ($isBusinessActivityChanged)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card rounded-0 shadow-none border">
                                <div class="card-header bg-white font-weight-bold">ISIIC Configurations</div>
                                <div class="card-body row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1">Select ISIIC I</label>
                                            <select class="form-control @error('isiic_i') is-invalid @enderror"
                                                wire:change="isiiciChange($event.target.value)" wire:model="isiic_i">
                                                <option value="null" disabled selected>Select</option>
                                                @foreach ($isiiciList as $row)
                                                    <option value="{{ $row->id }}">{{ $row->description }}</option>
                                                @endforeach
                                            </select>
                                            @error('isiic_i')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1">Select ISIIC II</label>
                                            <select class="form-control @error('isiic_ii') is-invalid @enderror"
                                                wire:change="isiiciiChange($event.target.value)" wire:model="isiic_ii">
                                                <option value='null' disabled selected>Select</option>
                                                @foreach ($isiiciiList as $row)
                                                    <option value="{{ $row->id }}">{{ $row->description }}</option>
                                                @endforeach
                                            </select>
                                            @error('isiic_ii')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1">Select ISIIC III</label>
                                            <select class="form-control @error('isiic_iii') is-invalid @enderror"
                                                wire:change="isiiciiiChange($event.target.value)" wire:model="isiic_iii">
                                                <option value="null" disabled selected>Select</option>
                                                @foreach ($isiiciiiList as $row)
                                                    <option value="{{ $row->id }}">{{ $row->description }}</option>
                                                @endforeach
                                            </select>
                                            @error('isiic_iii')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1">Select ISIIC IV</label>
                                            <select class="form-control @error('isiic_iv') is-invalid @enderror" wire:model="isiic_iv">
                                                <option value="null" disabled selected>Select</option>
                                                @foreach ($isiicivList as $row)
                                                    <option value="{{ $row->id }}">{{ $row->description }}</option>
                                                @endforeach
                                            </select>
                                            @error('isiic_iv')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                    
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($isLocationChanged)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card rounded-0 shadow-none border">
                                <div class="card-header bg-white font-weight-bold">Tax Region Configurations</div>
                                <div class="card-body row">
                    
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1">Tax Region</label>
                                            <select class="form-control @error('selectedTaxRegion') is-invalid @enderror"
                                                wire:model.defer="selectedTaxRegion">
                                                <option value="null" disabled selected>Select</option>
                                                @foreach ($taxRegions as $region)
                                                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('selectedTaxRegion')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif 
            @endif

            <div class="row mt-2">
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
        @if ($this->checkTransition('registration_officer_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'application_filled_incorrect')">Filed
                    Incorrect return to Applicant
                </button>
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'registration_officer_review')">Approve & Forward</button>
            </div>
        @elseif ($this->checkTransition('registration_manager_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger" wire:click="confirmPopUpModal('reject', 'registration_manager_reject')">Reject
                    & Return</button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'registration_manager_review')"
                    wire:loading.attr="disabled">
                    <div wire:loading wire:target="confirmPopUpModal('approve', 'registration_manager_review')">
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
