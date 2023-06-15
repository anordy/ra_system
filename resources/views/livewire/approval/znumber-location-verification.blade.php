<div class="col-md-12">
    <div class="row">
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Previous Z Number</span>
            <p class="my-1">{{ $location->business->previous_zno ?? 'N/A' }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Business Name</span>
            <p class="my-1">{{ $location->business->name }}</p>
        </div>

        @if (count($business_unit) > 0)
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Status</span>
                    <p class="my-1">
                        <span class="badge badge-success py-1 px-2"
                            style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            Verification Successful
                        </span>
                    </p>
            </div>
        @endif
    </div>

    @if ($units && count($units) > 0 && count($business_unit) == 0)
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover table-sm">
                    <label class="font-weight-bold text-uppercase">ZNUMBER Verification Information</label>
                    <p class="small">* Select a unit to make to link it to this business branch</p>
                    <thead>
                        <th>No</th>
                        <th>Unit Name</th>
                        <th>Business Name</th>
                        <th>Trade Name</th>
                        <th>Street</th>
                        <th>Tax Type</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                        @foreach ($units as $index => $unit)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $unit->unit_name ?? 'N/A' }}</td>
                                <td>{{ $unit->business_name ?? 'N/A' }}</td>
                                <td>{{ $unit->trade_name ?? 'N/A' }}</td>
                                <td>{{ $unit->street ?? 'N/A' }}</td>
                                <td>{{ $unit->taxtype->name ?? 'N/A' }}</td>
                                <td><input type="checkbox" wire:model="selectedUnit.{{ $unit->id }}"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

            <hr>
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
</div>
