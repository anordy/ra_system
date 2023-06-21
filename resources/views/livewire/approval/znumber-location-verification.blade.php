<div class="col-md-12">
    @if(!$location->vfms_associated_at)
        <div>
            @if(!$location->ward->vfms_ward && !$location->business->headquarter->ward->vfms_ward)
                <p class="small text-danger">* Ward; {{ $location->ward->name }} for this business location is not recognized to VFMS, contact Admin to complete this action</p>
            @endif
        </div>
    @endif
    <div class="row">
        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Previous Z Number</span>
            <p class="my-1">{{ $location->business->previous_zno ?? 'N/A' }}</p>
        </div>
        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Branch Location (Ward)</span>
            <p class="my-1">{{ $location->ward->name }}</p>
        </div>

        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Business Name</span>
            <p class="my-1">{{ $location->business->name }}</p>
        </div>

        @if($location->ward->vfms_ward && $location->business->headquarter->ward->vfms_ward)
            @if ($location->business->previous_zno && $fetch && !$location->vfms_associated_at)
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Action</span>
                    <p class="my-1">
                        <button wire:click="verifyZNumber" wire:loading.attr="disabled" class="btn btn-info btn-sm">
                            <div wire:loading wire:target="verifyZNumber">
                                <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            Fetch VFMS Business Unit
                        </button>
                    </p>
                </div>
            @else
                @if ($location->vfms_associated_at)
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Status</span>
                        <p class="my-1">
                            {{ \Carbon\Carbon::parse($location->vfms_associated_at)->format('l, F j, Y H:i') }}
                        </p>
                    </div>
                @endif
            @endif
        @else
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Action</span>
                <p class="my-1 font-italic text-warning">
                    Contact Admin To Complete this action.
                </p>
            </div>
        @endif
    </div>

    @if (count($business_units) <= 0 && $is_requested)
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover table-sm">
                    <label class="font-weight-bold text-uppercase">VFMS Business Unit(s) Information</label>
                    <p class="small">* Select a unit(s) to link it to this business branch</p>
                    <div class="">
                        <div class="small">* <span class="font-weight-bold">Integration status</span> indicate the <span class="font-weight-bold">business</span> internal system(s) are integrated to VFMS directly</div>
                        <div class="ml-2 small">i.e. Transactions and receipts are done and generated directly from the internal systems.</div>
                    </div>
                    <thead>
                        <th>No</th>
                        <th>Unit Name</th>
                        <th>Business Name</th>
                        <th>Trade Name</th>
                        <th>Street</th>
                        <th>Tax Type</th>
                        <th>integration Status</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    @if($units && count($units) > 0)
                        @if(!$fetch)
                            @foreach ($units as $index => $unit)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $unit->unit_name ?? 'N/A' }}</td>
                                    <td>{{ $unit->business_name ?? 'N/A' }}</td>
                                    <td>{{ $unit->trade_name ?? 'N/A' }}</td>
                                    <td>{{ $unit->street ?? 'N/A' }}</td>
                                    <td>{{ $unit->taxtype->name ?? 'N/A' }}</td>
                                    <td>{{ $unit->integration ? 'Integrated' : 'Not integrated' ?? 'N/A' }}</td>
                                    <td class="font-weight-bold {{ $unit['integration'] ? 'text-success' : 'text-muted' }}">{{ $unit['integration'] ? 'Integrated' : 'Not integrated' ?? 'N/A' }}</td>
                                    <td><input type="checkbox" wire:model="selectedUnit.{{ $unit->id }}"></td>
                                </tr>
                            @endforeach
                        @else
                            @foreach ($units as $index => $unit)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $unit['unit_name'] ?? 'N/A' }}</td>
                                    <td>{{ $unit['business_name'] ?? 'N/A' }}</td>
                                    <td>{{ $unit['trade_name'] ?? 'N/A' }}</td>
                                    <td>{{ $unit['street'] ?? 'N/A' }}</td>
                                    <td>{{ strtoupper($this->mapVfmsTaxType($unit['tax_type'])) ?? 'N/A' }}</td>
                                    <td class="font-weight-bold {{ $unit->integration ? 'text-success' : 'text-muted' }}">{{ $unit->integration ? 'Integrated' : 'Not integrated' ?? 'N/A' }}</td>
                                    <td><input type="checkbox" wire:model="units.{{ $index }}.is_selected"></td>
                                </tr>
                            @endforeach
                        @endif
                    @else
                        <tr>
                            <td colspan="10" class="text-center">
                                No data related!
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        @if($units && count($units) > 0)
            <p class="small text-danger">* Make sure to select only one unit if two business units returned with the same Tax Type and the same integration Status i.e VAT</p>
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-footer p-2 m-0">
                        <button wire:click="complete()" wire:loading.attr="disabled" class="btn btn-success">
                            <div wire:loading wire:target="complete">
                                <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>Complete Linking Branch with VFMS Unit
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @else
        @if($business_units->count())
            <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover table-sm">
                    <label class="font-weight-bold text-uppercase">VFMS Linked Business Units</label>
                    <div class="">
                        <div class="small">* <span class="font-weight-bold">Integration status</span> indicate the <span class="font-weight-bold">business</span> internal system(s) are integrated to VFMS directly.</div>
                        <div class="ml-2 small">i.e. Transactions and receipts are done and generated directly from the internal systems.</div>
                    </div>
                    <thead>
                        <th>No</th>
                        <th>Unit Name</th>
                        <th>Business Name</th>
                        <th>Trade Name</th>
                        <th>Street</th>
                        <th>Tax Type</th>
                        <th>integration Status</th>
                    </thead>
                    <tbody>
                        @foreach ($business_units as $index => $unit)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $unit->unit_name ?? 'N/A' }}</td>
                                <td>{{ $unit->business_name ?? 'N/A' }}</td>
                                <td>{{ $unit->trade_name ?? 'N/A' }}</td>
                                <td>{{ $unit->street ?? 'N/A' }}</td>
                                <td>{{ $unit->taxtype->name ?? 'N/A' }}</td>
                                <td class="font-weight-bold {{ $unit['integration'] ? 'text-success' : 'text-muted' }}">{{ $unit['integration'] ? 'Integrated' : 'Not integrated' ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @endif
</div>
