<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
             <div class="row">
                    <div class="form-group col-lg-4">
                        <label class="control-label">Principal Amount</label>
                        <input x-data x-mask:dynamic="$money($input)" type="text" class="form-control @error('principal') is-invalid @enderror"
                            wire:model.defer="principal">
                        @error('principal')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="control-label">Penalty Amount</label>
                        <input x-data x-mask:dynamic="$money($input)" type="text" class="form-control @error('penalty') is-invalid @enderror"
                            wire:model.defer="penalty">
                        @error('penalty')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="control-label">Interest Amount</label>
                        <input x-data x-mask:dynamic="$money($input)" type="text" class="form-control @error('interest') is-invalid @enderror"
                            wire:model.defer="interest">
                        @error('interest')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
