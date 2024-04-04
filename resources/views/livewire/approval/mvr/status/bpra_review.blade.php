<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                 <div class="row">
                    <div class="form-group col-lg-4">
                        <label class="control-label">Agreement Contract @if(!$subject->agreement_contract_path) * @endif</label>
                        <input type="file" class="form-control  @error('agreementContract') is-invalid @enderror"
                            wire:model.lazy="agreementContract">
                        @error('agreementContract')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
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
        </div>
    </div>
</div>
