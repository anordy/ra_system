<div>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">VFMS Data Integration For {{$location->name}}</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
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
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Saved At</span>
                            <p class="my-1">
                                {{ \Carbon\Carbon::parse($location->vfms_associated_at)->format('l, F j, Y H:i') }}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-hover table-sm">
                                <label class="font-weight-bold text-uppercase">VFMS Business Unit(s) Information</label>
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
                                @if($business_units && count($business_units) > 0)
                                    @foreach ($business_units as $index => $unit)
                                        <tr>
                                            <td>{{ $index + 1 }}.</td>
                                            <td>{{ $unit->unit_name ?? 'N/A' }}</td>
                                            <td>{{ $unit->business_name ?? 'N/A' }}</td>
                                            <td>{{ $unit->trade_name ?? 'N/A' }}</td>
                                            <td>{{ $unit->street ?? 'N/A' }}</td>
                                            <td>{{ $unit->taxtype->name ?? 'N/A' }}</td>
                                            <td class="font-weight-bold {{ $unit['integration'] ? 'text-success' : 'text-muted' }}">{{ $unit['integration'] ? 'Integrated' : 'Not integrated' ?? 'N/A' }}</td>
                                            <td>
                                                <input type="checkbox" wire:model.lazy="selectedUnit.{{ $unit->id }}">
                                            </td>
                                        </tr>
                                        @if(count($unit->getChildrenBusinessUnits($unit['unit_id'])))
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
                                                        @foreach($unit->getChildrenBusinessUnits($unit['unit_id']) as $childKey => $child)
                                                            <tr>
                                                                <td>{{ romanNumeralCount($childKey + 1) }}.</td>
                                                                <td>{{ $child['unit_name'] ?? 'N/A' }}</td>
                                                                <td>{{ $unit->taxtype->name ?? 'N/A' }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            No data related!
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if($business_units && count($business_units) > 0)
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
                                                </div>Complete Linking Branch with VFMS Unit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

