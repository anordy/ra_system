<div class="card rounded-0">
    <div class="card-header bg-white font-weight-bold text-uppercase">
        Institution Details
    </div>
    <div class="card-body">
        <div class="pt-1">
            <div class="border-0">
                <div class="row mx-4 mt-2">
                    <div class="col-md-4 form-group">
                        <label for="tin">Institution Tax Identification No. (TIN) *</label>
                        <input type="number" maxlength="10" minlength="8" wire:model.lazy="tin" required
                            class="form-control {{ $errors->has('tin') ? 'is-invalid' : '' }}">
                        @error('tin')
                            <div class="invalid-feedback">
                                {{ $errors->first('tin') }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="institution_name">Institution Name *</label>
                        <input type="text" wire:model.lazy="institution_name"
                            class="form-control {{ $errors->has('institution_name') ? 'is-invalid' : '' }}">
                        @error('institution_name')
                            <div class="invalid-feedback">
                                {{ $errors->first('institution_name') }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="address">Institution Address *</label>
                        <input type="text" wire:model.lazy="address"
                            class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}">
                        @error('address')
                            <div class="invalid-feedback">
                                {{ $errors->first('address') }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="institution_place">Place Of Institution *</label>
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
                        <label for="mobile">Institution Contact Number *</label>
                        <input type="text" maxlength="10" wire:model.lazy="mobile" name="mobile" id="mobile"
                            class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}">
                        @error('mobile')
                            <div class="invalid-feedback">
                                {{ $errors->first('mobile') }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="alt_mobile">Institution Alternative Contact Number</label>
                        <input type="text" maxlength="10" wire:model.lazy="alt_mobile" name="alt_mobile" id="alt_mobile"
                            class="form-control {{ $errors->has('alt_mobile') ? 'is-invalid' : '' }}">
                        @error('alt_mobile')
                            <div class="invalid-feedback">
                                {{ $errors->first('alt_mobile') }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="fax">Institution Fax Number</label>
                        <input type="text" maxlength="10" wire:model.lazy="fax" name="fax" id="fax"
                            class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}">
                        @error('fax')
                            <div class="invalid-feedback">
                                {{ $errors->first('fax') }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="email">Institution Email Address *</label>
                        <input type="email" wire:model.lazy="email" name="email" id="email"
                            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Region *</label>
                        <select wire:model.lazy="region_id"
                            class="form-control {{ $errors->has('region_id') ? 'is-invalid' : '' }}">
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
                        <label>District *</label>
                        <select wire:model.lazy="district_id"
                            class="form-control {{ $errors->has('district_id') ? 'is-invalid' : '' }}">
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
                        <label>Ward *</label>
                        <select wire:model.lazy="ward_id"
                            class="form-control {{ $errors->has('ward_id') ? 'is-invalid' : '' }}">
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
                        <label for="date_of_commencing">Date of Commencing *</label>
                        <input type="date" wire:model.lazy="date_of_commencing" name="date_of_commencing"
                            id="date_of_commencing"
                            class="form-control {{ $errors->has('date_of_commencing') ? 'is-invalid' : '' }}">
                        @error('date_of_commecning')
                            <div class="invalid-feedback">
                                {{ $errors->first('date_of_commencing') }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-header bg-white font-weight-bold text-uppercase">
        Responsible Person Details
    </div>
    <div class="card-body">
        <div>
                <div class="border-0">
                    <div class="row mx-4">
                        <div class="col-md-4 form-group">
                            <label for="ztnNumber">ZTN Number *</label>
                            <input type="text" wire:model.lazy="ztnNumber" name="ztnNumber" id="ztnNumber"
                                class="form-control {{ $errors->has('ztnNumber') ? 'is-invalid' : '' }}">
                            @error('ztnNumber')
                                <div class="invalid-feedback">
                                    {{ $errors->first('ztnNumber') }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="reference_no">Responsible person ZRB Reference No. *</label>
                            <input type="text" wire:model.lazy="reference_no" name="reference_no" id="reference_no"
                                class="form-control {{ $errors->has('reference_no') ? 'is-invalid' : '' }}">
                            @error('reference_no')
                                <div class="invalid-feedback">
                                    {{ $errors->first('reference_no') }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-4 mt-4 form-group">
                            <button wire:click="searchResponsibleDetails" wire:loading.attr="disabled"
                                class="btn btn-primary">
                                <div wire:loading.delay wire:target="searchResponsiblePerson">
                                    <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>Search
                            </button>
                        </div>
                    </div>

            {{-- Responsible Person Designation Fill up --}}
            @if ($search_triggered && !empty($taxpayer))
                <div class="row mx-4">
                    <div class="col-md-4 form-group">
                        <label for="title">Title of Responsible Person</label>
                        <select wire:model.lazy="title"
                            class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}">
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
                        <label for="position">Position of Responsible Person</label>
                        <input type="text" wire:model.lazy="position" name="position" id="position"
                            class="form-control {{ $errors->has('position') ? 'is-invalid' : '' }}">
                        @error('position')
                            <div class="invalid-feedback">
                                {{ $errors->first('position') }}
                            </div>
                        @enderror
                    </div>
                </div>
            @endif
        </div>

        {{-- Responsible person lookup --}}
        @if ($search_triggered && !empty($taxpayer) && !empty($business))
            <div class="row mx-4">
                <h6 class="pb-2">Responsible Person Details</h6>
                <div class="col-12 p-3">
                    <div class="card-body mb-2" style="border: 1px solid #ede6e6;">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Business Name</span>
                                <p class="my-1">{{ $business->name }}</p>
                            </div>

                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">TIN</span>
                                <p class="my-1">{{ $business->tin }}</p>
                            </div>

                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Email Address</span>
                                <p class="my-1">{{ $business->email }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Mobile/Alternative</span>
                                <p class="my-1">{{ $business->mobile }} / {{ $business->alt_mobile }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="border: 1px solid #ede6e6;">

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Full Name</span>
                                <p class="my-1">
                                    {{ "{$taxpayer->first_name} {$taxpayer->middle_name} {$taxpayer->last_name}" }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">TIN</span>
                                <p class="my-1">{{ "{$taxpayer->tin}" }}</p>
                            </div>

                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Email Address</span>
                                <p class="my-1">{{ $taxpayer->email }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Mobile/Alternative</span>
                                <p class="my-1">{{ $taxpayer->mobile }} / {{ $taxpayer->alt_mobile }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Nationality</span>
                                <p class="my-1">{{ $taxpayer->country->nationality }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ $taxpayer->identification->name }}
                                    No.</span>
                                <p class="my-1">{{ $taxpayer->id_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($search_triggered && (empty($taxpayer) || empty($business)))
            <span class="pt-2 text-danger text-center"> ZTN Number or ZRB Reference Number provided is invalid
            </span>
        @endif

        @if (!empty($taxpayer) && !empty($business))
        <hr>
        <div class="row mt-3 m-4">
            <div class="col-md-12 text-right">
                <a href="{{ route('withholdingAgents.list') }}" class="btn btn-danger mr-2">Cancel</a>
                <button wire:click="submit" wire:loading.attr="disabled" class="btn btn-primary">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Submit
                </button>
            </div>
        </div> 
        @endif

    </div>
</div>
</div>
</div>
