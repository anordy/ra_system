<div class="col-md-12">
    <div class="row">
        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Previous Z Number</span>
            <p class="my-1">{{ $business->previous_zno ?? 'N/A' }}</p>
        </div>
        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Headquarter Location (Ward)</span>
            <p class="my-1">{{ $business->headquarter->ward->name }}</p>
        </div>
        <div class="col-md-3 mb-3">
            <span class="font-weight-bold text-uppercase">Business Name</span>
            <p class="my-1">{{ $business->name }}</p>
        </div>
        @if($business->invalid_z_number)
            @if ($business->previous_zno && !$business->znumber_verified_at)
                <div class="col-md-3 mb-3">
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
                    <p class="my-1">
                        <button wire:click="confirmPopUpModal('returnForCorrection')" wire:loading.attr="disabled" class="btn btn-danger btn-sm">
                            <div wire:loading wire:target="returnForCorrection">
                                <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            Return For Correction.
                        </button>
                    </p>
                </div>
            @else
                <div class="col-md-3 mb-3">
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
        @else
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Status</span>
                <p class="my-1">
                    <span class="badge badge-warning py-1 px-2"
                          style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
                        <i class="bi bi-clock-history mr-1"></i>
                        Waiting for correction
                    </span>
                </p>
            </div>
        @endif
    </div>

    @if ($response && is_array($response) && count($response) > 0)
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover table-sm">
                    <label class="font-weight-bold text-uppercase">VFMS Business Unit(s) Information</label>
                    <p class="small">* Select a unit to make it a headquarter</p>
                    <thead>
                        <th>No</th>
                        <th>Unit Name</th>
                        <th>Business Name</th>
                        <th>Trade Name</th>
                        <th>Street</th>
                        <th>Tax Type</th>
                        <th>No of Children</th>
                        <th>integration Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($response as $index => $unit)
                            <tr>
                                <td>{{ $index + 1 }}.</td>
                                <td>{{ $unit['unit_name'] ?? 'N/A' }}</td>
                                <td>{{ $unit['business_name'] ?? 'N/A' }}</td>
                                <td>{{ $unit['trade_name'] ?? 'N/A' }}</td>
                                <td>{{ $unit['street'] ?? 'N/A' }}</td>
                                <td>{{ strtoupper($this->mapVfmsTaxType($unit['tax_type'])) ?? 'N/A' }}</td>
                                <td>{{ count($unit['children']) }}</td>
                                <td class="font-weight-bold {{ $unit['integration'] ? 'text-success' : 'text-muted' }}">{{ $unit['integration'] ? 'Integrated' : 'Not integrated' ?? 'N/A' }}</td>
                                <td><input type="checkbox" wire:model="response.{{ $index }}.is_headquarter"></td>
                            </tr>
                            @if(count($unit['children']))
                                <tr>
                                    <td colspan="9" class="px-4 border rounded">
                                        <table class="table table-sm px-2">
                                            <label class="font-weight-bold"> {{ $unit['unit_name'] }} associated Business unit(s)</label>
                                            <thead>
                                                <th>No</th>
                                                <th>Unit Name</th>
                                                <th>Tax Type</th>
                                            </thead>
                                            <tbody>
                                                @foreach($unit['children'] as $childKey => $child)
                                                    <tr>
                                                        <td>{{ romanNumeralCount($childKey + 1) }}.</td>
                                                        <td>{{ $child['unit_name'] ?? 'N/A' }}</td>
                                                        <td>{{ strtoupper($this->mapVfmsTaxType($unit['tax_type'])) ?? 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="small text-danger">* Make sure to select only one unit if two business units returned with the same Tax Type and  integration Status i.e VAT</div>
        <div class="small text-danger">* For business unit with associated units, if selected all associated business units are also selected.</div>
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

</div>
