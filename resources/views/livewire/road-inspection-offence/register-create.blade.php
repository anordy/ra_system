<div>
    <div class="row m-2">
        <div class="col-md-12 form-group">
            <label for="zin">Driver's License Number</label>
            <input type="text" wire:model.lazy="lin"
                   class="form-control {{ $errors->has('lin') ? 'is-invalid' : '' }}">
            @error('lin')
            <div class="invalid-feedback">
                {{ $errors->first('lin') }}
            </div>
            @enderror
        </div>

        <div class="col-md-12">
            <button wire:click="licenseLookup" wire:loading.attr="disabled" class="btn btn-primary">
                <div wire:loading wire:target="submit">
                    <div class="spinner-border mr-1 spinner-border-sm text-light">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                Lookup
            </button>
        </div>
        <br>
        <br>
        <div class="col-md-12 form-group">
            <br>
            @if($license_lookup_fired && !empty($license))
                @php($taxpayer = $license->drivers_license_owner->taxpayer)
                <h5>License Details</h5>
                <hr/>
                <div class="row my-2">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Full Name</span>
                        <p class="my-1">{{ "{$taxpayer->first_name} {$taxpayer->middle_name} {$taxpayer->last_name}" }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Email Address</span>
                        <p class="my-1">{{ $taxpayer->email }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Mobile</span>
                        <p class="my-1">{{ $taxpayer->mobile }}</p>
                    </div>
                </div>
                <hr/>
                <div class="row my-2">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">License Classes</span>
                        <p class="my-1">
                            @foreach($license->drivers_license_classes()->get() as $class)
                                {{ $class->license_class->name }},
                            @endforeach
                        </p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Issued Date</span>
                        <p class="my-1">{{ $license->issued_date }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Expire Date</span>
                        <p class="my-1">{{ $license->expiry_date }}</p>
                    </div>
                </div>
            @elseif($license_lookup_fired)
                <p class="my-1 text-danger"> The supplied License number does not exist </p>
            @endif
        </div>
    </div>

    <div class="row m-2">
        <div class="col-md-12 form-group">
            <label for="zin">Plate Number</label>
            <input type="text" wire:model.lazy="plate_number"
                   class="form-control {{ $errors->has('plate_number') ? 'is-invalid' : '' }}">
            @error('plate_number')
            <div class="invalid-feedback">
                {{ $errors->first('plate_number') }}
            </div>
            @enderror
        </div>

        <div class="col-md-12">
            <button wire:click="plateNumberLookup" wire:loading.attr="disabled" class="btn btn-primary">
                <div wire:loading wire:target="submit">
                    <div class="spinner-border mr-1 spinner-border-sm text-light">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                Lookup
            </button>
        </div>
        <br>
        <br>
        <div class="col-md-12 form-group">
            <br>
            @if($plate_lookup_fired && !empty($mvr))
                <h5>Motor Vehicle Details</h5>
                <hr/>
                <div class="row my-2">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Make</span>
                        <p class="my-1">{{ $mvr->motor_vehicle->model->make->name}}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Modal</span>
                        <p class="my-1">{{ $mvr->motor_vehicle->model->name}}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Owner</span>
                        <p class="my-1">{{ $mvr->motor_vehicle->current_owner->taxpayer->fullname()??''}}</p>
                    </div>
                </div>
            @elseif($plate_lookup_fired)
                <p class="my-1 text-danger"> The supplied plate number does not exist </p>
            @endif
        </div>
    </div>

    @if(!empty($mvr) && !empty($license))
     <div class="row">
        <div class="col-12 m-3">
            <div class="form-group">
                <label for="offences">Select Offences</label>
                <select class="form-control"  wire:model.lazy="offences" multiple>
                    @foreach (\App\Models\RioOffence::all() as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </select>
                @error('offences')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="block_type">Apply Restrictions</label>
                <select class="form-control"  wire:model.lazy="block_type" >
                    <option>Choose Option</option>
                    <option value="NONE">None</option>
                    <option value="PLATE NUMBER">Plate Number</option>
                    <option value="LICENSE">Drivers License</option>
                </select>
                @error('block_type')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="d-flex">
                <button wire:click="submit" wire:loading.attr="disabled" class="btn btn-primary justify-content-end">
                    <div wire:loading wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    Submit
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
