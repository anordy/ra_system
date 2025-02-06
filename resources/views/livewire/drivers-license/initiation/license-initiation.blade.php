<div>
    <div class="card rounded-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 pt-3">
                    <h4>Initiate Driver's License Application</h4>
                    <p class="mb-3">Provide the required applicant information to continue</p>
                    <hr/>
                </div>
                <x-input col="3" type="text" name="firstName" label="First Name" required></x-input>
                <x-input col="3" type="text" name="middleName" label="Middle Name"></x-input>
                <x-input col="3" type="text" name="lastName" label="Last Name" required></x-input>

                {{--    TODO: Add restriction on age   --}}
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="control-label">Date of Birth *</label>
                        <input type="date" class="form-control" wire:model="dob">
                        @error('dob')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <x-input col="3" type="text" name="confirmationNumber" label="Confirmation Number" required></x-input>
                <div class="col-md-12 pt-3">
                    <h6>License Details</h6>
                    <hr/>
                </div>
                <x-multiple-select col="3" name="restrictionId" :options="$restrictions" accessor="description"
                                   label="License Restriction" required></x-multiple-select>
                <x-select col="3" name="bloodGroupId" :options="$bloodGroups" label="Blood Group" required></x-select>

            </div>

            <h6>License Class</h6>
            <hr/>
            @foreach($licenseClasses ?? [] as $i => $class)
                <div class="row">
                    <x-select col="3" name="licenseClasses.{{$i}}.classId" :options="$classes" label="Class"
                              required></x-select>
                    <x-input col="3" type="text" name="licenseClasses.{{$i}}.certificateNumber" label="Certificate Number" required></x-input>
                    <x-input col="3" type="date" name="licenseClasses.{{$i}}.certificateDate" label="Certificate Date" required></x-input>

                    <button class="btn btn-success" wire:click="removeClass({{$i}})">
                        <i class="bi bi-plus-circle-fill mr-1"></i>Remove Class
                    </button>
                </div>
            @endforeach
            <button class="btn btn-success d-flex column-gap-2" wire:click="addClass()">
                <i class="bi bi-plus-circle-fill mr-1"></i> {{ __('Add Class') }}
            </button>

        </div>
    </div>
</div>