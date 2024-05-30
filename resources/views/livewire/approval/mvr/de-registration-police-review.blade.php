<div>
    @if($deregistration->reason->name === \App\Enum\MvrDeRegistrationReasonStatus::LOST && $this->checkTransition('mvr_police_officer_review'))
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label>Reasons For A Lost Motor Vehicle *</label>
                    <textarea class="form-control @error('reasonsForLost') is-invalid @enderror" wire:model.defer='reasonsForLost' rows="3"></textarea>
                    @error('reasonsForLost')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>
    @elseif($deregistration->reason->name === \App\Enum\MvrDeRegistrationReasonStatus::OUT_OF_ZANZIBAR && $this->checkTransition('mvr_police_officer_review'))
        <div class="row">
            <div class="form-group col-lg-6">
                <label class="control-label">Evidence of Clearance *</label>
                <input type="file" class="form-control  @error('clearanceEvidence') is-invalid @enderror"
                       wire:model.defer="clearanceEvidence">
                @error('clearanceEvidence')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label>Reasons for De-registration *</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" wire:model.defer='description' rows="3"></textarea>
                    @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>
    @elseif(($deregistration->reason->name === \App\Enum\MvrDeRegistrationReasonStatus::SCRAPPED || $deregistration->reason->name === \App\Enum\MvrDeRegistrationReasonStatus::NOT_UNDER_OBLIGATION) && $this->checkTransition('mvr_registration_officer_review'))
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label>Reasons for De-registration *</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" wire:model.defer='description' rows="3"></textarea>
                    @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>

    @else
        Reason: {{ $description ?? 'N/A' }}
    @endif

</div>