<div>
    @if ($errors->any())
        <div class="alert alert-danger p-0 pt-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row m-2">
        <div class="col-md-4 mb-3">
            <label>Property Name/Number *</label>
            <input minlength="3" maxlength="50" type="text"
                   class="form-control no-arrow @error('name') is-invalid @enderror" wire:model.defer="name" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label>Region *</label>
            <select class="form-control @error('region_id') is-invalid @enderror" wire:model="region_id">
                <option></option>
                @foreach ($regions as $region)
                    <option value="{{ $region['id'] }}">{{ $region['name'] }}</option>
                @endforeach
            </select>
            @error('region_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label>District *</label>
            <select class="form-control @error('district_id') is-invalid @enderror" wire:model="district_id">
                <option></option>
                <option wire:loading wire:target="region_id">
                    Loading...
                </option>
                @foreach ($districts as $district)
                    <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                @endforeach
            </select>
            @error('district_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label>Ward *</label>
            <select class="form-control @error('ward_id') is-invalid @enderror" wire:model="ward_id">
                <option></option>
                <option wire:loading wire:target="district_id">
                    Loading...
                </option>
                @foreach ($wards as $ward)
                    <option value="{{ $ward['id'] }}">{{ $ward['name'] }}</option>
                @endforeach
            </select>
            @error('ward_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label>Street *</label>
            <select class="form-control @error('street_id') is-invalid @enderror" wire:model="street_id">
                <option></option>
                <option wire:loading wire:target="ward_id">
                    Loading...
                </option>
                @foreach ($streets as $street)
                    <option value="{{ $street['id'] }}">{{ $street['name'] }}</option>
                @endforeach
            </select>
            @error('street_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label>Property Status *</label>
            <select class="form-control @error('status') is-invalid @enderror" wire:model.defer="status">
                <option></option>
                <option value="complete">Completed</option>
                <option value="incomplete">Incomplete</option>
            </select>
            @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

    </div>

    <div class="card-body">
        <div class="pt-4">
            @foreach($storeys as $i => $item)
                <div class="row mb-3">
                    <div class="col-md-12 form-group">
                        <label class="font-weight-bold text-uppercase">Storey Number {{ $i+1  }}</label>
                        <hr class="mt-1">
                        @foreach($item as $j => $unit)
                            <div class="row mb-3">
                                <div class="col-md-4 mb-3">
                                    <label>Unit Name/Number *</label>
                                    <input minlength="3" maxlength="50" type="text"
                                           class="form-control no-arrow @error('name') is-invalid @enderror"
                                           wire:model.defer="storeys.{{$i}}.{{$j}}.name" required>
                                    @error('storeys.*.*.name')
                                    <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mt-4">
                                    <button class="btn btn-outline-danger mt-1"
                                            wire:click="removeUnit({{ $i }}, {{ $j }})">
                                        <i class="bi bi-x-circle-fill mr-2"></i>
                                        Remove Unit
                                    </button>
                                </div>

                            </div>

                        @endforeach
                        <div class="col-md-4 mt-4">
                            <button class="btn btn-outline-success mt-1" wire:click="addUnit({{ $i }}, {{ $i }})">
                                <i class="bi bi-x-circle-fill mr-2"></i>
                                Add Unit
                            </button>
                        </div>

                        <div class="col-md-4 mt-4">
                            @if(count($storeys) > 1)
                                <button class="btn btn-outline-danger mt-1" wire:click="removeStorey({{ $i }})">
                                    <i class="bi bi-x-circle-fill mr-2"></i>
                                    Remove Storey
                                </button>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <hr/>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row mt-3">
            <div class="col-md-12 d-flex justify-content-end">
                <button class="btn btn-success mr-2" wire:click="addStorey()" wire:loading.class="disabled">
                    <i class="bi bi-plus-circle-fill mr-2"></i>
                    Add Storey
                </button>
            </div>
        </div>

    </div>

    <div class="row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <button class="btn btn-primary ml-1" wire:click="submit()" wire:loading.attr="disabled">
                Save
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="submit"></i>
            </button>
        </div>
    </div>
</div>