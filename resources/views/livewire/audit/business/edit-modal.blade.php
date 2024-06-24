<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Edit Business to Audit</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Select Business</label>
                        <select wire:model="business_id" class="form-control @error('business_id') is-invalid @enderror"
                            wire:change="businessChange($event.target.value)">
                            <option value="">Select Business</option>
                            @foreach ($business as $row)
                                <option value="{{ encrypt($row->id) }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        @error('business_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Select Location</label>
                        <select wire:model="location_id"
                            class="form-control @error('location_id') is-invalid @enderror">
                            <option value="">Select Branch</option>
                            @if ($locations)
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">
                                        {{ $location->is_headquarter ? $location->street->name . ' - HQ' : $location->street->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('location_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Tax Type</label>
                        <select wire:model="tax_type_id"
                            class="form-control @error('tax_type_id') is-invalid @enderror">
                            <option value="">Select Tax Type</option>
                            @if ($taxTypes)
                                @foreach ($taxTypes as $taxType)
                                    <option value="{{ $taxType->id }}">{{ $taxType->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('tax_type_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Auditing From</label>
                        <input type="date" class="form-control" wire:model.defer="period_from" id="period_from">
                        @error('period_from')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="control-label">Auditing To</label>
                        <input type="date" class="form-control" wire:model.defer="period_to" id="period_to">
                        @error('period_to')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12"></div>
                    <div class="col-lg-6 form-group">
                        <label for="intension">Intension</label>
                        <textarea class="form-control" wire:model.defer="intension" id="intension" rows="3"></textarea>
                        @error('intension')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="periodTo">Scope</label>
                        <textarea class="form-control" wire:model.defer="scope" id="scope" rows="3"></textarea>
                        @error('scope')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
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
                    </div>Update changes</button>
            </div>
        </div>
    </div>
</div>
