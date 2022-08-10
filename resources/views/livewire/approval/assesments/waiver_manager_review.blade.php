<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label class="control-label">Weaver Report</label>
                        <input type="file" class="form-control  @error('weaverReport') is-invalid @enderror"
                            wire:model.lazy="weaverReport">
                        @error('weaverReport')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
