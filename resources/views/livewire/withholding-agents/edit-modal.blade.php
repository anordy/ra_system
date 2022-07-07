<div class="modal-dialog modal-xl">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="text-uppercase">Edit Withholding Agent</h5>
    </div>
    <div class="modal-body">
        <div class="card border-0">
            <h5 class="card-title text-uppercase mx-4">Main Details</h5>
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
                    <label for="mobile">Contact Number</label>
                    <input type="text" maxlength="10" wire:model.lazy="mobile" name="mobile" id="mobile"
                        class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}">
                    @error('mobile')
                        <div class="invalid-feedback">
                            {{ $errors->first('mobile') }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="email">Email Address</label>
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
       
        <div class="card mt-4 border-0">
            <h5 class="card-title text-uppercase mx-4">Responsible Person Details</h5>
            <div class="row mx-4 mt-2">
            <div class="col-md-4 form-group">
                <label>Responsible Person Name</label>
                <select wire:model="responsible_person_id" class="form-control {{ $errors->has('responsible_person_id') ? 'is-invalid' : '' }}">
                    <option></option>
                    @foreach ($responsible_persons as $responsible_person)
                        <option value="{{ $responsible_person->id }}">
                            {{ $responsible_person->first_name . ' ' . $responsible_person->middle_name . ' ' . $responsible_person->last_name }}</option>
                    @endforeach
                </select>
                @error('responsible_person_id')
                    <div class="invalid-feedback">
                        {{ $errors->first('responsible_person_id') }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4 form-group">
                <label for="title">Title</label>
                <select wire:model="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}">
                    <option></option>
                    <option value="Mr">Mr</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Sir">Sir</option>
                    <option value="Madam">Madam</option>
                    <option value="Dr">Dr</option>
                    <option value="Prof">Prof</option>
                    <option value="Hon">Hon</option>
                    <option value="Other">Other</option>
                </select>
                @error('title')
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4 form-group">
                <label for="position">Position</label>
                <input type="text" wire:model.lazy="position" name="position" id="position"
                    class="form-control {{ $errors->has('position') ? 'is-invalid' : '' }}">
                @error('position')
                    <div class="invalid-feedback">
                        {{ $errors->first('position') }}
                    </div>
                @enderror
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" wire:click='submit'>Update changes</button>
    </div>
</div>
</div>
