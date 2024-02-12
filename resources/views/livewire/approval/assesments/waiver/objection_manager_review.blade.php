<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-4">
                        <label class="control-label">Waiver Report/ Dispute Report</label>
                        <input type="file" class="form-control  @error('disputeReport') is-invalid @enderror"
                            wire:model.lazy="disputeReport">
                        @error('disputeReport')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
             
            </div>
        </div>
    </div>
    <div class="text-secondary small">
        <span class="font-weight-bold">
            {{ __('Note') }}:
        </span>
            <span class="">
            {{ __('Uploaded Documents must be less than 3  MB in size') }}
        </span>
    </div>
</div>
