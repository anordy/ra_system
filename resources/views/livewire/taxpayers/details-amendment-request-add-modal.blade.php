<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Taxpayer Details Amendment Request For {{$taxpayer->fullName()}}</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-md-4">
                        <label class="control-label">First Name</label>
                        <input type="text" class="form-control" wire:model.defer="first_name" id="first_name">
                        @error('first_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Middle Name</label>
                        <input type="text" class="form-control" wire:model.defer="middle_name" id="middle_name">
                        @error('middle_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Last Name</label>
                        <input type="text" class="form-control" wire:model.defer="last_name" id="last_name">
                        @error('last_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Email</label>
                        <input type="email" class="form-control" wire:model.defer="email" id="email">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Phone Number</label>
                        <input type="text" class="form-control" wire:model.defer="mobile" id="mobile">
                        @error('mobile')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Alternative Phone Number</label>
                        <input type="text" class="form-control" wire:model.defer="alt_mobile" id="alt_mobile">
                        @error('alt_mobile')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Region  *</label>
                        <select class="form-control @error('region') is-invalid @enderror" wire:model.lazy="region">
                            <option></option>
                            @foreach ($regions as $regionObject)
                                <option {{ $regionObject->id == $region ? 'selected' : '' }} value="{{ $regionObject->id }}">{{ $regionObject->name }}</option>
                            @endforeach
                        </select>
                        @error('region')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>District  *</label>
                        <select class="form-control @error('district') is-invalid @enderror" wire:model.lazy="district">
                            <option></option>
                            @foreach ($districts as $districtObject)
                                <option {{ $districtObject->id == $district ? 'selected' : '' }} value="{{ $districtObject->id }}">{{ $districtObject->name }}</option>
                            @endforeach
                        </select>
                        @error('district')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Ward  *</label>
                        <select class="form-control @error('ward') is-invalid @enderror" wire:model.lazy="ward">
                            <option></option>
                            @foreach ($wards as $wardObject)
                                <option {{ $wardObject->id == $ward ? 'selected' : '' }} value="{{ $wardObject->id }}">{{ $wardObject->name }}</option>
                            @endforeach
                        </select>
                        @error('ward')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Street *</label>
                        <select class="form-control @error('street') is-invalid @enderror" wire:model.lazy="street">
                            <option></option>
                            @foreach ($streets as $streetObject)
                                <option {{ $streetObject->id == $street ? 'selected' : '' }} value="{{ $streetObject->id }}">{{ $streetObject->name }}</option>
                            @endforeach
                        </select>
                        @error('street')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Physical Address</label>
                        <input type="text" class="form-control" wire:model.defer="physical_address" id="physical_address">
                        @error('physical_address')
                            <span class="text-danger">{{ $message }}</span>
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
                    </div>Save changes</button>
            </div>
        </div>
    </div>
</div>
