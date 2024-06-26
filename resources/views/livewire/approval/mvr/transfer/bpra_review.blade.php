<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                 <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="control-label">Sales Agreement @if(!$subject->agreement_contract_path) * @endif</label>
                        <input type="file" class="form-control  @error('agreementContract') is-invalid @enderror"
                            wire:model.lazy="agreementContract">
                        @error('agreementContract')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                     <div class="form-group col-lg-3">
                         <label class="control-label">Affidavit @if(!$subject->affidavit) * @endif</label>
                         <input type="file" class="form-control  @error('affidavit') is-invalid @enderror"
                                wire:model.lazy="affidavit">
                         @error('affidavit')
                         <span class="text-danger">{{ $message }}</span>
                         @enderror
                     </div>
                     <div class="form-group col-lg-3">
                         <label class="control-label">Original Card @if(!$subject->original_card) * @endif</label>
                         <input type="file" class="form-control  @error('originalCard') is-invalid @enderror"
                                wire:model.lazy="originalCard">
                         @error('originalCard')
                         <span class="text-danger">{{ $message }}</span>
                         @enderror
                     </div>
                     <div class="form-group col-lg-3">
                         <label class="control-label">Owner's Identification @if(!$subject->owner_id) * @endif</label>
                         <input type="file" class="form-control  @error('ownerId') is-invalid @enderror"
                                wire:model.lazy="ownerId">
                         @error('ownerId')
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
