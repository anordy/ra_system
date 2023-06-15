<div class="col-md-12">
    <div class="row">
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Previous Z Number</span>
            <p class="my-1">{{ $business->previous_zno ?? 'N/A' }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Business Name</span>
            <p class="my-1">{{ $business->name }}</p>
        </div>
        @if ($business->previous_zno && !$business->znumber_verified_at)
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Action</span>
                <p class="my-1">
                    <button wire:click="verifyZNumber" wire:loading.attr="disabled" class="btn btn-info btn-sm">
                        <div wire:loading wire:target="verifyZNumber">
                            <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>Verify Z-Number
                    </button>
                </p>
            </div>
        @else
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Status</span>
                @if ($business->znumber_verified_at && $business->previous_zno)
                    <p class="my-1">
                        <span class="badge badge-success py-1 px-2"
                            style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            Verification Successful
                        </span>
                    </p>
                @endif
            </div>
        @endif
    </div>

    @if ($response && is_array($response) && count($response) > 0)
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover table-sm">
                    <label class="font-weight-bold text-uppercase">ZNUMBER Verification Information</label>
                    <p class="small">* Select a unit to make it a headquarter</p>
                    <thead>
                        <th>No</th>
                        <th>Unit Name</th>
                        <th>Business Name</th>
                        <th>Trade Name</th>
                        <th>Street</th>
                        <th>Tax Type</th>
                    </thead>
                    <tbody>
                        @foreach ($response as $index => $unit)
                            <tr wire:click="selectHeadquarter({{ $index }})">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $unit['unit_name'] ?? 'N/A' }}</td>
                                <td>{{ $unit['business_name'] ?? 'N/A' }}</td>
                                <td>{{ $unit['trade_name'] ?? 'N/A' }}</td>
                                <td>{{ $unit['street'] ?? 'N/A' }}</td>
                                <td>{{ strtoupper($this->mapVfmsTaxType($unit['tax_type'])) ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($selectedUnitHeadquarter)
            <hr>
            <label class="font-weight-bold text-uppercase">Selected Headquarter</label>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Unit Name</span>
                    <p class="my-1">{{ $selectedUnitHeadquarter['unit_name'] ?? '' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $selectedUnitHeadquarter['business_name'] ?? '' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Trade Name</span>
                    <p class="my-1">{{ $selectedUnitHeadquarter['trade_name'] ?? '' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Street</span>
                    <p class="my-1">{{ $selectedUnitHeadquarter['street'] ?? '' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Type</span>
                    <p class="my-1">{{ strtoupper($this->mapVfmsTaxType($selectedUnitHeadquarter['tax_type'])) ?? '' }}</p>
                </div>
            </div>
        @endif

        @if (!$business->znumber_verified_at)
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-footer p-2 m-0">
                        <button wire:click="complete()" wire:loading.attr="disabled" class="btn btn-success">
                            <div wire:loading wire:target="complete">
                                <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>Complete ZNUMBER Verification
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endif


</div>
