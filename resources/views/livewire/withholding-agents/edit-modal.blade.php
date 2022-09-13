<div class="modal-dialog modal-xl">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="text-uppercase">Edit Withholding Agent</h5>
    </div>
    <div class="modal-body">
        <div class="border-0">
            <div class="row mx-4 mt-2">
                <div class="col-md-4 form-group">
                    <label for="tin">Tax Identification No. (TIN)</label>
                    <input type="text" maxlength="9" wire:model.lazy="tin"
                        class="form-control {{ $errors->has('tin') ? 'is-invalid' : '' }}">
                    @error('tin')
                        <div class="invalid-feedback">
                            {{ $errors->first('tin') }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="institution_name">Institution Name</label>
                    <input type="text" wire:model.lazy="institution_name"
                        class="form-control {{ $errors->has('institution_name') ? 'is-invalid' : '' }}">
                    @error('institution_name')
                        <div class="invalid-feedback">
                            {{ $errors->first('institution_name') }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="address">Address</label>
                    <input type="text" wire:model.lazy="address"
                        class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}">
                    @error('address')
                        <div class="invalid-feedback">
                            {{ $errors->first('address') }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="institution_place">Place Of Institution</label>
                    <input type="text" wire:model.lazy="institution_place" name="institution_place"
                        id="institution_place"
                        class="form-control {{ $errors->has('institution_place') ? 'is-invalid' : '' }}">
                    @error('institution_place')
                        <div class="invalid-feedback">
                            {{ $errors->first('institution_place') }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="mobile">Institution Contact Number</label>
                    <input type="text" maxlength="10" wire:model.lazy="mobile" name="mobile" id="mobile"
                        class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}">
                    @error('mobile')
                        <div class="invalid-feedback">
                            {{ $errors->first('mobile') }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="email">Institution Email Address</label>
                    <input type="email" wire:model.lazy="email" name="email" id="email"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Region</label>
                    <select wire:model="region_id" class="form-control {{ $errors->has('region_id') ? 'is-invalid' : '' }}">
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
                <div class="col-md-4 form-group">
                    <label>District</label>
                    <select wire:model="district_id" class="form-control {{ $errors->has('district_id') ? 'is-invalid' : '' }}">
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
                <div class="col-md-4 form-group">
                    <label>Ward</label>
                    <select wire:model="ward_id" class="form-control {{ $errors->has('ward_id') ? 'is-invalid' : '' }}">
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
                <div class="col-md-4 form-group">
                    <label for="date_of_commencing">Date of Commencing</label>
                    <input type="date" wire:model.lazy="date_of_commencing" name="date_of_commencing"
                        id="date_of_commencing" class="form-control {{ $errors->has('date_of_commencing') ? 'is-invalid' : '' }}">
                    @error('date_of_commecning')
                        <div class="invalid-feedback">
                            {{ $errors->first('date_of_commencing') }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
            <div wire:loading wire:target="submit">
                <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
             </div>
            Save changes</button>
    </div>
</div>
</div>
