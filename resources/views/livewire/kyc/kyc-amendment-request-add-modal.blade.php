<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">KYC Details Amendment Request For {{$kyc->fullName()}}</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-4">
                        <label class="control-label">First Name</label>
                        <input type="text" class="form-control" wire:model.defer="first_name" id="first_name">
                        @error('first_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="control-label">Middle Name</label>
                        <input type="text" class="form-control" wire:model.defer="middle_name" id="middle_name">
                        @error('middle_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="control-label">Last Name</label>
                        <input type="text" class="form-control" wire:model.defer="last_name" id="last_name">
                        @error('last_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="control-label">Email</label>
                        <input type="email" class="form-control" wire:model.defer="email" id="email">
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="control-label">Phone Number</label>
                        <input type="text" class="form-control" wire:model.defer="mobile" id="mobile">
                        @error('mobile')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
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
                        <select class="form-control @error('street') is-invalid @enderror" wire:model.defer="street">
                            <option></option>
                            @foreach ($streets as $streetObject)
                                <option {{ $streetObject->id == $street ? 'selected' : '' }} value="{{ $streetObject->id }}">{{ $streetObject->name }}</option>
                            @endforeach
                        </select>
                        @error('street')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="control-label">Physical Address</label>
                        <input type="text" class="form-control" wire:model.defer="physical_address" id="physical_address">
                        @error('physical_address')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Are you a citizen ?  *</label>
                        <select class="form-control @error('is_citizen') is-invalid @enderror" wire:model.lazy="is_citizen" id="is_citizen">
                            <option value="" selected>Choose Citizenship</option>
                            <option {{$is_citizen == '1' ? 'selected' : ''}} value="1">Yes</option>
                            <option {{$is_citizen == '0' ? 'selected' : ''}} value="0">No</option>
                        </select>
                        @error('is_citizen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                @if ($is_citizen === '1')
                            <div class="form-group col-lg-6">
                                <label>NIDA</label>
                                <input type="text" maxlength="20" class="form-control no-arrow @error('nida') is-invalid @enderror"
                                       wire:model.lazy="nida">
                                @error('nida')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-lg-6">
                                <label>ZANID</label>
                                <input type="text" maxlength="9" class="form-control no-arrow @error('zanid') is-invalid @enderror"
                                       wire:model.lazy="zanid">
                                @error('zanid')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @elseif($is_citizen === '0')
                            <div class="form-group col-lg-6">
                                <label>Nationality *</label>
                                <select class="form-control @error('nationality') is-invalid @enderror"
                                        wire:model.lazy="nationality">
                                    @foreach ($countries as $country)
                                        <option value="{{ $country['id'] }}">{{ $country['nationality'] }}</option>
                                    @endforeach
                                </select>
                                @error('nationality')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Passport Number *</label>
                                <input type="text" maxlength="15"
                                       class="form-control @error('passportNo') is-invalid @enderror" wire:model.lazy="passportNo">
                                @error('passportNo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Permit Number *</label>
                                <input type="text" maxlength="20"
                                       class="form-control @error('permitNumber') is-invalid @enderror"
                                       wire:model.lazy="permitNumber">
                                @error('permitNumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
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
