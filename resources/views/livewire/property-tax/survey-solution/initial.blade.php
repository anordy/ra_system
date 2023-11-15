<div class="container-fluid mb-sm-4">
    <div class="card text-left rounded-0">
        <div class="card-body">
            <div class="p-3">
                <h3>Property Tax Registration</h3>
                <p>Please select Owner Search Type, supported searches are NIDA, ZANID, PASSPORT, TIN, ZRA NUMBER, ZRA
                    REFERENCE NO and MOBILE Phone.</p>
                <hr/>
                <div class="row">

                    <div class="form-group col-md-6">
                        <label></label>
                        <select class="form-control @error('identifierType') is-invalid @enderror"
                                wire:model.defer="identifierType">
                            <option value="">{{ __('Please choose identifier Type') }}...</option>
                            <option value="NIDA">NIDA</option>
                            <option value="ZANID">ZANID</option>
                            <option value="PASSPORT">PASSPORT NUMBER</option>
                            <option value="TIN">TIN NUMBER</option>
                            <option value="ZRA_NUMBER">ZRA NUMBER</option>
                            <option value="ZRA_REF_NO">ZRA REFERENCE NUMBER</option>
{{--                            <option value="MOBILE">MOBILE NUMBER</option>--}}
                        </select>
                        @error('identifierType')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label></label>
                        <input type="text"
                               class="form-control @error('identifierNumber') is-invalid @enderror"
                               wire:model.defer="identifierNumber" required placeholder="Enter Identifier Number">
                        @error('identifierNumber')
                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-12 text-center">
                        <button class="btn btn-primary rounded-0 px-3" wire:click="search()">
                            <i class="bi bi-search mr-2"></i>
                            {{ __('Search') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(is_array($properties) && $properties[0])
        <div class="card text-left rounded-0">
            <div class="card-body">
                <div class="p-3">
                    <h5>Property Owner Information</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Name</span>
                            <p class="my-1">{{ $properties[0]['owner']['fullName'] ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Email</span>
                            <p class="my-1">{{ $properties[0]['owner']['email_address'] ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Mobile</span>
                            <p class="my-1">{{ $properties[0]['owner']['phone_no'] ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">ZRA Number</span>
                            <p class="my-1">{{ $properties[0]['owner']['zra_number'] ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">ZRA Reference Number</span>
                            <p class="my-1">{{ $properties[0]['owner']['zra_ref_no'] ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">TIN</span>
                            <p class="my-1">{{ $properties[0]['owner']['tin'] ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">NIDA</span>
                            <p class="my-1">{{ $properties[0]['owner']['nida'] ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">ZANID</span>
                            <p class="my-1">{{ $properties[0]['owner']['zanID'] ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Passport Number</span>
                            <p class="my-1">{{ $properties[0]['owner']['passport'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="px-3">
                        <h6>Additional Property Owner Information</h6>
                        <hr>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Ownership Type *</label>
                                <select class="form-control @error('ownershipType') is-invalid @enderror" wire:model="ownershipType">
                                    <option></option>
                                    @foreach ($ownershipTypes as $ownerType)
                                        <option value="{{ $ownerType->name }}">{{ formatEnum($ownerType->name) }}</option>
                                    @endforeach
                                </select>
                                @error('ownershipType')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @if($ownershipType === \App\Enum\PropertyOwnershipTypeStatus::GOVERNMENT || $ownershipType === \App\Enum\PropertyOwnershipTypeStatus::RELIGIOUS)
                                <div class="col-md-4 mb-3">
                                    <label>Institution Name *</label>
                                    <input type="text"
                                           class="form-control no-arrow @error('institutionName') is-invalid @enderror"
                                           wire:model.defer="institutionName" required>
                                    @error('institutionName')
                                    <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif
                            @if(isset($properties[0]['owner']['passport']) && !is_null($properties[0]['owner']['passport']))
                                <div class="col-md-4 mb-3">
                                    <label>Nationality *</label>
                                    <select class="form-control @error('nationality') is-invalid @enderror" wire:model.defer="nationality">
                                        <option>Select Nationality</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country['id'] }}">{{ $country['nationality'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('nationality') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Permit Number *</label>
                                    <input type="text" maxlength="20" class="form-control @error('permitNumber') is-invalid @enderror" wire:model.defer="permitNumber">
                                    @error('permitNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(is_array($properties))
        @foreach($properties as $index => $property)
            <div class="card text-left rounded-0">
                <div class="card-body">
                    <div class="p-3">
                        <h5>Property Information</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Property Type</span>
                                <p class="my-1">{{ $property['property_type'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Property Name</span>
                                <p class="my-1">{{ $property['property_name'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Property Address</span>
                                <p class="my-1">{{ $property['property_address'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Region</span>
                                <p class="my-1">{{ $property['region'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">District</span>
                                <p class="my-1">{{ $property['district'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Locality</span>
                                <p class="my-1">{{ $property['locality'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Hotel Stars</span>
                                <p class="my-1">{{ $property['hotel_star'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Type of Business</span>
                                <p class="my-1">{{ $property['type_of_business'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Number of Storey</span>
                                <p class="my-1">{{ $property['number_of_storey'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Meter number</span>
                                <p class="my-1">{{ $property['meter_no'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Post Code</span>
                                <p class="my-1">{{ $property['postcode'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Property Feature</span>
                                <p class="my-1">{{ $property['property_feature'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="px-3">
                        <h6>Additional Property Information</h6>
                        <hr>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Name</label>
                                <input minlength="3" maxlength="50" type="text"
                                       class="form-control no-arrow @error('properties.' .$index. '.name') is-invalid @enderror" wire:model.defer="properties.{{$index}}.name" required>
                                @error('properties.' .$index. 'name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Property Size (Square Foot)</label>
                                <input type="text"
                                       class="form-control no-arrow @error('properties.' .$index. 'size') is-invalid @enderror"
                                       wire:model.defer="properties.{{$index}}.size" required>
                                @error('properties.' .$index. 'size')
                                <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Property Value</label>
                                <input type="text"
                                       class="form-control no-arrow @error('properties.' .$index. 'propertyValue') is-invalid @enderror"
                                       wire:model.defer="properties.{{$index}}.propertyValue" required>
                                @error('properties.' .$index. 'propertyValue')
                                <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Purchase Value</label>
                                <input type="text"
                                       class="form-control no-arrow @error('properties.' .$index. 'purchaseValue') is-invalid @enderror"
                                       wire:model.defer="properties.{{$index}}.purchaseValue" required>
                                @error('properties.' .$index. 'purchaseValue')
                                <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Acquisition Date</label>
                                <input type="date" class="form-control @error('properties.' .$index. 'acquisitionDate') is-invalid @enderror"
                                       wire:model.lazy="properties.{{$index}}.acquisitionDate" required>
                                @error('properties.' .$index. 'acquisitionDate')
                                <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="p-3">
                        <h5>Property Agent Information</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Agent Name</span>
                                <p class="my-1">{{ $property['agent']['name_of_person'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Agent Company</span>
                                <p class="my-1">{{ $property['agent']['name_of_company'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Mobile</span>
                                <p class="my-1">{{ $property['agent']['phone_no_1'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Alt Mobile</span>
                                <p class="my-1">{{ $property['agent']['phone_no_2'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Email</span>
                                <p class="my-1">{{ $property['agent']['email'] ?? 'N/A' }}</p>
                            </div>

                            @if(array_key_last($properties) === $index)
                                <hr>
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-primary rounded-0 px-3" wire:click="submit()">
                                        Submit
                                    </button>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

</div>
