<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="text-uppercase">Edit Ward</h5>
        </div>
        <div class="modal-body">
            <div class="card border-0">
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
                        <input type="text" class="form-control" wire:model.lazy="name" id="name">
                        @error('ward_id')
                            <div class="invalid-feedback">
                                {{ $errors->first('ward_id') }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>



        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" wire:click='submit'>Update Changes</button>
        </div>
    </div>

</div>
</div>
