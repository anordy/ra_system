<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Initiate Audit</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label> Business</label>
                        <input type="text" disabled class="form-control" value="{{ $business->name }}" id="business_id">
                        @error('business_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Location</label>
                        <select wire:model="location_ids" multiple disabled
                            class="form-control @error('location_ids') is-invalid @enderror">
                            @if ($locations)
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">
                                        {{ $location->is_headquarter ? $location->street->name . ' - HQ' : $location->street->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('location_ids')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Tax Type</label>
                        <select wire:model="tax_type_ids" multiple disabled
                            class="form-control @error('tax_type_ids') is-invalid @enderror">
                            @if ($taxTypes)
                                @foreach ($taxTypes as $taxType)
                                    <option value="{{ $taxType->id }}">{{ $taxType->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('tax_type_ids')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Auditing From</label>
                        <input type="date" class="form-control" wire:model.lazy="period_from" id="period_from">
                        @error('period_from')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="control-label">Auditing To</label>
                        <input type="date" class="form-control" wire:model.lazy="period_to" id="period_to">
                        @error('period_to')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12"></div>
                    <div class="col-lg-6 form-group">
                        <label for="intension">Intension</label>
                        <textarea class="form-control" wire:model.lazy="intension" id="intension" rows="3"></textarea>
                        @error('intension')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="periodTo">Scope</label>
                        <textarea class="form-control" wire:model.lazy="scope" id="scope" rows="3"></textarea>
                        @error('scope')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Add Audit</button>
            </div>
        </div>
    </div>
</div>
