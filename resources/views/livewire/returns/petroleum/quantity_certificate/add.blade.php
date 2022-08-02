<div class="card">
    <div class="card-body">
        <div class="row pr-3 pl-3">

            <div class="form-group col-lg-6">
                <label class="control-label">Name of Importer/Market (Z_Number)</label>
                <input type="text" class="form-control @error('business') is-invalid @enderror" wire:model.lazy="business">
                @error('business')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Ascertained Date</label>
                <div type="text" class="form-control disabled">{{ $ascertained }}</div>
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Name of Ship</label>
                <input type="text" class="form-control @error('ship') is-invalid @enderror" wire:model.lazy="ship">
                @error('ship')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group col-lg-6">
                <label class="control-label">Port of Disembarkation</label>
                <input type="text" class="form-control @error('port') is-invalid @enderror" wire:model.lazy="port">
                @error('port')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            
        </div>
        <div class="row pr-3 pl-3">
            <div class="form-group col-lg-6">
                <label class="control-label">Intended Cargo Discharge</label>
                <input type="text" class="form-control @error('cargo') is-invalid @enderror" wire:model.lazy="cargo">
                @error('cargo')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Liters Observed</label>
                <input type="number" class="form-control @error('liters_observed') is-invalid @enderror" wire:model.lazy="liters_observed">
                @error('liters_observed')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Liters At 20 <sup>o</sup> C</label>
                <input type="number" class="form-control @error('liters_at_20') is-invalid @enderror" wire:model.lazy="liters_at_20">
                @error('liters_at_20')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Metric Tons in Air</label>
                <input type="number" class="form-control @error('metric_tons') is-invalid @enderror" wire:model.lazy="metric_tons">
                @error('metric_tons')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" wire:click='submit'>Save changes</button>
    </div>
</div>
