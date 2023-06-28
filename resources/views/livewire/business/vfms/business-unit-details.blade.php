<div class="col-md-12">
    <div class="pt-2 mb-0 font-weight-bold text-sm text-uppercase">VFMS Business Unit(s) Information</div>
    <hr class="mt-1 mb-2" />
    <table class="table table-striped table-hover table-sm">
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
            </thead>
            <tbody>
                @if(count($businessUnits) > 0)
                    @foreach ($businessUnits as $index => $unit)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $unit->unit_name ?? 'N/A' }}</td>
                            <td>{{ $unit->business_name ?? 'N/A' }}</td>
                            <td>{{ $unit->trade_name ?? 'N/A' }}</td>
                            <td>{{ $unit->street ?? 'N/A' }}</td>
                            <td>{{ $unit->taxtype->name ?? 'N/A' }}</td>
                            <td class="font-weight-bold {{ $unit['integration'] ? 'text-success' : 'text-muted' }}">{{ $unit['integration'] ? 'Integrated' : 'Not integrated' ?? 'N/A' }}</td>
                        </tr>
                        @if(count($unit->getChildrenBusinessUnits($unit->unit_id)))
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
                                        @foreach($unit->getChildrenBusinessUnits($unit->unit_id) as $childKey => $child)
                                            <tr>
                                                <td>{{ romanNumeralCount($childKey + 1) }}.</td>
                                                <td>{{ $child->unit_name ?? 'N/A' }}</td>
                                                <td>{{ $child->taxtype->name ?? 'N/A' }}</td>
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
</div>
