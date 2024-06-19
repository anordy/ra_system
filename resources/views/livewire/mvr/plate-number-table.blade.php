<div>
    <div class="text-left">
        @if($plate_number_status === \App\Models\MvrPlateNumberStatus::STATUS_GENERATED)
            <button class="btn btn-sm btn-primary mb-2" @if ($plateNumbers->isEmpty()) disabled @endif wire:click="printedBulk">
                <i class="bi bi-ui-checks pr-1"></i> Update As Printed
            </button>
        @elseif($plate_number_status === \App\Models\MvrPlateNumberStatus::STATUS_PRINTED)
            <button class="btn btn-sm btn-primary mb-2" @if ($plateNumbers->isEmpty()) disabled @endif wire:click="receivedBulk">
                <i class="bi bi-ui-checks pr-1"></i> Update As Received
            </button>
        @endif
    </div>
    <table class="table table-hover table-bordered table-sm align-middle">
        <thead>
        <tr>
            @if(in_array($plate_number_status, [\App\Models\MvrPlateNumberStatus::STATUS_GENERATED, \App\Models\MvrPlateNumberStatus::STATUS_PRINTED]))
                <th width="10">
                {{-- <input type="checkbox" @if ($plateNumbers->isEmpty()) disabled @endif>--}}
                </th>
            @endif
            <th>Chassis Number</th>
            <th>Registration No.</th>
            <th>Serial No.</th>
            <th>Reg Type</th>
            <th>Plate Color</th>
            <th>Plate Size</th>
            <th>Registration Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @if ($plateNumbers->isNotEmpty())
            @foreach ($plateNumbers as $row)
                <tr>
                    @if(in_array($plate_number_status, [\App\Models\MvrPlateNumberStatus::STATUS_GENERATED, \App\Models\MvrPlateNumberStatus::STATUS_PRINTED]))
                        <td><input width="10" type="checkbox" wire:model.defer="selectedItems.{{ $row->id }}"></td>
                    @endif
                    <td>{{ $row->chassis->chassis_number }}</td>
                    <td>{{ $row->plate_number }}</td>
                    <td>{{ $row->registration_number }}</td>
                    <td>{{ $row->regtype? $row->regtype->name : 'N/A' }}</td>
                    <td>{{ $row->regtype ? $row->regtype->color->color : 'N/A' }}</td>
                    <td>{{ $row->platesize ? $row->platesize->name : 'n/a' }}</td>
                    <td>{{ $row->registered_at }}</td>
                    <td>
                        @if($plate_number_status === \App\Models\MvrPlateNumberStatus::STATUS_GENERATED)
                            <button class="btn btn-sm btn-primary" wire:click="printed({{ $row->id }})">
                                <i class="bi bi-check-circle-fill pr-1"></i> Updated as Printed
                            </button>
                        @elseif($plate_number_status === \App\Models\MvrPlateNumberStatus::STATUS_PRINTED)
                            <button class="btn btn-sm btn-primary" wire:click="received({{ $row->id }})">
                                <i class="bi bi-check-circle-fill pr-1"></i> Update as Received
                            </button>
                        @elseif($plate_number_status === \App\Models\MvrPlateNumberStatus::STATUS_RECEIVED)
                           <button class="btn btn-primary btn-sm" onclick="Livewire.emit('showModal', 'mvr.plate-number-collection-model', '{{ encrypt($row->id) }}')">
                               <i class="bi bi-check-circle-fill pr-1"></i> Update as Collected
                           </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5" class="text-center">There are no new notifications for you at the moment</td>
            </tr>
        @endif
        </tbody>
    </table>
    <div class="d-flex justify-content-end">
        {{ $plateNumbers->links() }}
    </div>
</div>
