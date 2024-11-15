<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Initiate Internal Information Change</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Select Information Type</label>
                        <select type="text" class="form-control" wire:model.lazy="informationType"
                                id="informationType">
                            <option>-------- Select Information Type -------</option>
                            <option value="hotelStars">Hotel Stars</option>
                            <option value="effectiveDate">Effective Date</option>
                            <option value="taxType">Tax Types</option>
                            <option value="isic">ISIIC Codes</option>
                            <option value="electric">Business Electric Status</option>
                            <option value="lto">Business LTO Status</option>
                            <option value="currency">Business Currency</option>
                            <option value="taxRegion">Tax Region</option>
                            <option value="businessOwnership">Business Ownership</option>
                        </select>
                        @error('informationType')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-8">
                        <label class="control-label">Enter Business Location ZIN Number</label>
                        <input type="text" class="form-control" wire:model.defer="zin" id="zin">
                        @error('zin')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-4">
                        <button type="button" wire:click="getZin()" class="btn btn-secondary mt-4">Search</button>
                    </div>
                </div>


                @if ($businessHotel)
                    <div class="text-uppercase font-weight-normal pr-3 pl-3 mb-4 mt-4">Hotel Star Rating Details
                        for {{ $location->business->name }} of Branch {{ $location->name }}
                        <hr>
                    </div>

                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-6">
                            <label class="control-label">Current Hotel Star Rating</label>
                            <input type="text" class="form-control" value="{{ $businessHotel->star->name ?? 'N/A' }}"
                                   disabled>
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">New Hotel Star Rating</label>
                            <select type="text" class="form-control" wire:model.lazy="newHotelStarId"
                                    id="newHotelStarId">
                                <option value="">--------- N/A ---------</option>
                                @foreach ($hotelStars as $hotelStar)
                                    <option value="{{ $hotelStar->id }}">{{ $hotelStar->name }}</option>
                                @endforeach
                            </select>
                            @error('newHotelStarId')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endif

                @if($currentEffectiveDate)
                    <div class="text-uppercase font-weight-normal pr-3 pl-3 mb-4 mt-4">Effective Date Details
                        for {{ $location->business->name }} of Branch {{ $location->name }}
                        <hr>
                    </div>

                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-6">
                            <label class="control-label">Current Effective Date</label>
                            <input type="text" class="form-control" value="{{ $currentEffectiveDate ?? 'N/A' }}"
                                   disabled>
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Effective Date</label>
                            <input type="date" wire:model.defer="newEffectiveDate" name="newEffectiveDate"
                                   id="newEffectiveDate" class="form-control"/>
                            @error('newEffectiveDate')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endif

                @if($taxTypes)
                    @include('livewire.internal-info-change.tax_type.reg_manager_review_tax_type')
                @endif

                @if($showElectric)
                    <div class="col-md-12">
                        <div class="card rounded-0 shadow-none border">
                            <div class="card-header font-weight-bold bg-white">Business Electric Configuration
                                for {{ $location->business->name }}</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label>
                                                <input type="checkbox" wire:model.defer="electricStatus">
                                                Is Business Electric
                                            </label>
                                            @error('electricStatus')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($showLto)
                    <div class="col-md-12">
                        <div class="card rounded-0 shadow-none border">
                            <div class="card-header font-weight-bold bg-white">Business LTO Configuration
                                for {{ $location->business->name }}</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label>
                                                <input type="checkbox" wire:model.lazy="ltoStatus">
                                                Is Business LTO
                                            </label>
                                            @error('ltoStatus')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif

                @if($informationType == 'taxRegion' && $taxRegionId)

                    <div class="col-md-6 ">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Tax Department</label>
                            <select class="form-control @error('selectedDepartment') is-invalid @enderror"
                                    wire:model.defer="selectedDepartment">
                                <option value="null" disabled selected>Select</option>
                                @foreach ($taxDepartment as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedDepartment')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 ">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Tax Region</label>
                            <select class="form-control @error('selectedTaxRegion') is-invalid @enderror"
                                    wire:model.defer="selectedTaxRegion">
                                <option value="null" disabled selected>Select</option>
                                @foreach ($taxRegions as $region)
                                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedTaxRegion')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                @endif

                @if($businessCurrencyId)
                    <div class="text-uppercase font-weight-normal pr-3 pl-3 mb-4 mt-4">Business Currency Details
                        for {{ $location->business->name }}
                        <hr>
                    </div>

                    <div class="row pr-3 pl-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Currency</label>
                                <select class="form-control @error('businessCurrencyId') is-invalid @enderror"
                                        wire:model.defer="businessCurrencyId">
                                    <option value="null" disabled selected>Select</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                                @error('taxRegionId')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif

                @if($isiiciList)
                    <div class="text-uppercase font-weight-normal pr-3 pl-3 mb-4 mt-4">ISIIC Details
                        for {{ $location->business->name }}
                        <hr>
                    </div>
                    <div class="row pr-3 pl-3">
                        <div class="col-md-12">
                            <div class="card rounded-0 shadow-none border">
                                <div class="card-header bg-white font-weight-bold">ISIIC Configurations</div>
                                <div class="card-body row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1">Select ISIIC I</label>
                                            <select class="form-control @error('isiic_i') is-invalid @enderror"
                                                    wire:change="isiiciChange($event.target.value)" wire:model="isiic_i">
                                                <option value="null" disabled selected>Select</option>
                                                @foreach ($isiiciList as $row)
                                                    <option value="{{ $row->id }}">{{ $row->description }}</option>
                                                @endforeach
                                            </select>
                                            @error('isiic_i')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1">Select ISIIC II</label>
                                            <select class="form-control @error('isiic_ii') is-invalid @enderror"
                                                    wire:change="isiiciiChange($event.target.value)" wire:model="isiic_ii">
                                                <option value='null' disabled selected>Select</option>
                                                @foreach ($isiiciiList as $row)
                                                    <option value="{{ $row->id }}">{{ $row->description }}</option>
                                                @endforeach
                                            </select>
                                            @error('isiic_ii')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1">Select ISIIC III</label>
                                            <select class="form-control @error('isiic_iii') is-invalid @enderror"
                                                    wire:change="isiiciiiChange($event.target.value)" wire:model="isiic_iii">
                                                <option value="null" disabled selected>Select</option>
                                                @foreach ($isiiciiiList as $row)
                                                    <option value="{{ $row->id }}">{{ $row->description }}</option>
                                                @endforeach
                                            </select>
                                            @error('isiic_iii')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1">Select ISIIC IV</label>
                                            <select class="form-control @error('isiic_iv') is-invalid @enderror" wire:model="isiic_iv">
                                                <option value="null" disabled selected>Select</option>
                                                @foreach ($isiicivList as $row)
                                                    <option value="{{ $row->id }}">{{ $row->description }}</option>
                                                @endforeach
                                            </select>
                                            @error('isiic_iv')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($informationType == 'businessOwnership' && $previousOwner)
                    <div class="pr-3 pl-3 mb-4 mt-4"><span class="text-uppercase font-weight-normal">Previous Owner Details</span>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="font-weight-bold">Business Name</label><br>
                                <p>{{ $location->name }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="font-weight-bold">Taxpayer Name</label><br>
                                <p>{{ $previousOwner->fullName }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="font-weight-bold">Taxpayer ZNO</label><br>
                                <p>{{ $previousOwner->reference_no }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-6">
                            <label class="font-weight-bold">New Owner ZNO</label>
                            <input type="text" class="form-control" placeholder="ZXXXXXXXXXXX" wire:model="newOwnerZno">
                            @error('newOwnerZno')
                            <small class="text-danger pt-2">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
