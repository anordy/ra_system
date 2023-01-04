<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header font-weight-bold h6">
            Add New Street
        </div>
        <div class="modal-body">
                <div class="row mx-4 mt-2">
                    <div class="col-md-12 form-group">
                        <label>Region</label>
                        <select wire:model="region_id" class="form-control">
                            <option></option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                        @error('region_id')
                        <div class="invalid-feedback">
                            {{ $errors->first('region_id') }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-12 form-group">
                        <label>District</label>
                        <select wire:model="district_id" class="form-control">
                            <option></option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                        @error('district_id')
                        <div class="invalid-feedback">
                            {{ $errors->first('district_id') }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Ward</label>
                        <select wire:model="ward_id" class="form-control">
                            <option></option>
                            @foreach ($wards as $ward)
                                <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                            @endforeach
                        </select>
                        @error('ward_id')
                        <div class="invalid-feedback">
                            {{ $errors->first('ward_id') }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Street</label>
                        <input type="text" class="form-control" wire:model.lazy="name" id="name">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @enderror
                    </div>
                </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
                <div wire:loading.delay wire:target="submit">
                    <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>Submit</button>
        </div>
    </div>

</div>
</div>
