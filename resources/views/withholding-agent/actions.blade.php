@can('withholding_agents_edit')
    <button class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="right" title="Edit"
        onclick="Livewire.emit('showModal', 'withholding-agents.withholding-agent-edit-modal',{{ $row->id }})"><i class="fa fa-edit"></i>
    </button>
@endcan

@can('withholding_agents_view')
    <button class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="right" title="View"
        onclick="Livewire.emit('showModal', 'withholding-agents.withholding-agent-view-modal',{{ $row->id }})"><i
            class="fa fa-eye"></i> </button>
@endcan

{{-- @can('withholding_agents_disable')
    @if ($row->status == 'active')
        <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="" wire:click="delete({{ $row->id }})"><i class="fa fa-ban"></i> </button>
    @else
        <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="" wire:click="delete({{ $row->id }})"><i class="fa fa-ban"></i> </button>
    @endif
@endcan --}}
