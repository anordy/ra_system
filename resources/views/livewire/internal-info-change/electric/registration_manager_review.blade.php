<div class="card rounded-0 shadow-none border">
    <div class="card-body">
<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-0">
            <label>
                <input type="checkbox" disabled wire:model.defer="currentElectricStatus">
                Current Business Electric
            </label>
            @error('electricStatus')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-0">
            <label>
                <input type="checkbox" wire:model.defer="electricStatus">
                New Business Electric
            </label>
            @error('electricStatus')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
</div>
    </div>
</div>