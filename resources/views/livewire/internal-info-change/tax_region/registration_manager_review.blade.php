
<div class="card rounded-0 shadow-none border">
    <div class="card-body">
        <div class="row pr-3 pl-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Current Tax Region</label>
                    <select class="form-control @error('currentTaxRegionId') is-invalid @enderror"
                            wire:model.defer="currentTaxRegionId" disabled>
                        <option value="null" disabled selected>Select</option>
                        @foreach ($taxRegions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                        @endforeach
                    </select>
                    @error('taxRegionId')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">New Tax Region</label>
                    <select class="form-control @error('taxRegionId') is-invalid @enderror"
                            wire:model.defer="taxRegionId">
                        <option value="null" disabled selected>Select</option>
                        @foreach ($taxRegions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                        @endforeach
                    </select>
                    @error('taxRegionId')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

        </div>

    </div>
</div>