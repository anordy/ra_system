<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Add Business to Investigations</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
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
                        <input
                                wire:model.defer="selectedBusiness"
                                type="text"
                                class="form-input form-control"
                                placeholder="Search Business..."
                                wire:model="query"
                                wire:keydown.escape="resetFields"
                                wire:keydown.tab="resetFields"
                                wire:keydown.arrow-up="decrementHighlight"
                                wire:keydown.arrow-down="incrementHighlight"
                                wire:keydown.enter="selectBusiness"
                        />
                        <div wire:loading class="absolute z-10 w-full bg-white rounded-t-none shadow-lg list-group">
                            <div class="list-item">Searching...</div>
                        </div>
                        @if(!empty($query))
                            <div class="fixed top-0 bottom-0 left-0 right-0" wire:click="resetFields"></div>

                            <div class="absolute z-10 w-full bg-white rounded-t-none shadow-lg list-group">
                                @if(!empty($business))
                                    @foreach($business as $i => $row)
                                        <a href="#" class="list-item {{ $highlightIndex === $i ? 'highlight' : '' }}"
                                        >{{ $row['name'] }} ({{ $row['ztn_number'] }})</a>
                                    @endforeach
                                @else
                                    <div class="list-item">No results!</div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Select Location</label>
                        <select wire:model="location_ids" multiple
                            class="form-control @error("location_ids") is-invalid @enderror">
                            <option value="">Select Branch</option>
                            @if ($locations)
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">
                                        {{ $location->is_headquarter ? $location->name . " - HQ" : $location->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error("location_ids")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Tax Type</label>
                        <select wire:model="tax_type_ids" multiple
                            class="form-control @error("tax_type_ids") is-invalid @enderror">
                            <option value="">Select Tax Type</option>
                            @if ($taxTypes)
                                @foreach ($taxTypes as $taxType)
                                    <option value="{{ $taxType->id }}">{{ $taxType->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error("tax_type_ids")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Investigation From</label>
                        <input type="date" class="form-control" wire:model.defer="period_from" id="period_from">
                        @error("period_from")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="control-label">Investigation To</label>
                        <input type="date" class="form-control" wire:model.defer="period_to" id="period_to">
                        @error("period_to")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12"></div>
                    <div class="col-lg-6 form-group">
                        <label for="allegations">Allegations</label>
                        <textarea class="form-control" wire:model.defer="allegations" id="allegations" rows="3"></textarea>
                        @error("allegations")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="periodTo">Descriptions</label>
                        <textarea class="form-control" wire:model.defer="descriptions" id="descriptions" rows="3"></textarea>
                        @error("descriptions")
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
                    </div>Add Investigation
                </button>
            </div>
        </div>
    </div>
</div>
