<div class="card rounded-0 shadow-none border">
    <div class="card-body">
<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-0">
            <label>
                <input type="checkbox" disabled wire:model.defer="currentltoStatus">
                Current Business LTO Status
            </label>
            @error('ltoStatus')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-0">
            <label>
                <input type="checkbox" wire:model.defer="ltoStatus">
                New Business LTO Status
            </label>
            @error('ltoStatus')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
</div>
    </div>
</div>
