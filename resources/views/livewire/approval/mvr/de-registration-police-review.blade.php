<div>
    @if($deregistration->reason->name === \App\Enum\MvrDeRegistrationReasonStatus::LOST)
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label>Reasons For A Lost Motor Vehicle</label>
                    <textarea class="form-control @error('reasonsForLost') is-invalid @enderror" wire:model.defer='reasonsForLost' rows="3"></textarea>
                    @error('reasonsForLost')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>
    @elseif($deregistration->reason->name === \App\Enum\MvrDeRegistrationReasonStatus::OUT_OF_ZANZIBAR)
        <div class="row">
            <div class="form-group col-lg-6">
                <label class="control-label">Evidence of Clearance</label>
                <input type="file" class="form-control  @error('clearanceEvidence') is-invalid @enderror"
                       wire:model.defer="clearanceEvidence">
                @error('clearanceEvidence')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    @elseif($deregistration->reason->name === \App\Enum\MvrDeRegistrationReasonStatus::SERVIER_ACCIDENT)
        <div class="row">
            <div class="form-group col-lg-6">
                <label class="control-label">ZIC Evidence</label>
                <input type="file" class="form-control  @error('zicEvidence') is-invalid @enderror"
                       wire:model.defer="zicEvidence">
                @error('zicEvidence')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    @else
        No Reason Provided
    @endif

</div>