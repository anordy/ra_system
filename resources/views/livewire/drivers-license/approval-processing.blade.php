<div class="card shadow-sm mb-2 bg-white">
    <div class="card-body m-0 pb-0">
        @include('livewire.approval.transitions')

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
    @if ($this->checkTransition('revenue_officer_review'))
        @if($this->subject->application_status->name === \App\Models\DlApplicationStatus::STATUS_PENDING_APPROVAL)
            <div class="row mt-2">
                <div  class="mb-3 col-md-12">
                    <div class="form-group">
                        <label for="zin" class="font-weight-bold text-uppercase">License Duration</label>
                        <select wire:model='duration_id' class="form-control {{ $errors->has('duration_id') ? 'is-invalid' : '' }}">
                            <option value>Choose Duration</option>
                            @foreach(\App\Models\DlLicenseDuration::all() as $group)
                                <option value="{{$group->id}}" {{$group->id == $this->subject->dl_license_duration_id}}>{{$group->number_of_years.' - '.$group->description}}</option>
                            @endforeach
                        </select>
                        @error('duration_id')
                        <div class="invalid-feedback">
                            {{ $errors->first('duration_id') }}
                        </div>
                    </div>
                    @enderror
                </div>
            </div>
        @endif

    </div>

        <div class="modal-footer p-2 m-0">
            <button type="button" class="btn btn-danger" wire:click="reject('application_filled_incorrect')">Filled Incorrect
                return to Transport Officer</button>
            <button type="button" class="btn btn-primary" wire:click="approve('revenue_officer_review')" wire:loading.attr="disabled">
                    <div wire:loading wire:target="approve('compliance_officer_review')">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    Confirm
            </button>
        </div>
    @endif

</div>
