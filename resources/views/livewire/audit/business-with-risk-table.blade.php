<div>
    <div class="d-flex justify-content-between mb-2">
        <button class="btn btn-primary" @if ($locations->isEmpty()) disabled @endif wire:click="confirmAddToAudit">
            <i class="bi bi-ui-checks pr-1"></i> Add <b>{{ count($selectedItems) ?: '' }}</b> Business To Audit
        </button>
        <div class="d-flex">
            <select wire:model="perPage" class="form-control w-50 mr-1">
                <option>15</option>
                <option>25</option>
                <option>50</option>
                <option>100</option>
            </select>
            <input class="form-control d-inline" placeholder="Type to Search..." wire:model="searchQuery">
        </div>
    </div>
    <table class="table table-hover table-bordered table-sm align-middle table-striped">
        <thead>
        <tr>
            <th width="10">
            </th>
            <th wire:click="setSortBy('zin')">
                ZRA No
                @if($sortBy !== 'zin')
                    <i class="bi bi-chevron-expand"></i>
                @else
                    @if($sortDirection == 'DESC')
                        <i class="bi bi-chevron-down"></i>
                    @else
                        <i class="bi bi-chevron-up"></i>
                    @endif
                @endif
            </th>
            <th>
                TIN
            </th>
            <th>Business Name</th>
            <th>Business Location</th>
            <th>Region</th>
            <th>District</th>
            <th>Ward</th>
            <th>Registered On</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @if ($locations->isNotEmpty())
            @foreach ($locations as $row)
                <tr>
                    <td class="p-0">
                        <label class="p-2 mb-0">
                            <input width="10" type="checkbox" wire:model="selectedItems.{{ $row->id }}">
                        </label>
                    </td>
                    <td>{{ $row->zin }}</td>
                    <td>{{ $row->business->tin }}</td>
                    <td>{{ $row->business->name }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->region->name }}</td>
                    <td>{{ $row->district->name }}</td>
                    <td>{{ $row->ward->name }}</td>
                    <td>{{ $row->approved_on }}</td>
                    <td>
                        <a href="{{ route('tax_auditing.business.show', encrypt($row->id)) }}"
                           class="btn btn-primary btn-sm" data-toggle="tooltip"
                           data-placement="right" title="View">
                            <i class="bi bi-eye-fill mr-1"></i> View More
                        </a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="10" class="text-center">There are no new businesses with risk indicators for you at the
                    moment
                </td>
            </tr>
        @endif
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <div>
            Showing {{ ($locations->currentPage() * $locations->perPage()) - ($locations->perPage() - 1) }}
            to {{ $locations->currentPage() * $locations->perPage() }} of {{ $locations->total() }} records.
        </div>
        <div>
            {{ $locations->links() }}
        </div>
    </div>
</div>
