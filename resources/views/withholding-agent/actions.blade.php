@can('withholding_agents_edit')
    <button class="btn btn-info btn-sm"
        onclick="Livewire.emit('showModal', 'withholding-agents.withholding-agent-edit-modal',{{ $row->id }})"><i class="fa fa-edit"></i>
    </button>
@endcan

@can('withholding_agents_view')
    <button class="btn btn-success btn-sm"
        onclick="Livewire.emit('showModal', 'withholding-agents.withholding-agent-view-modal',{{ $row->id }})"><i
            class="fa fa-eye"></i> </button>
@endcan

@can('withholding_agents_disable')
    <button class="btn btn-danger btn-sm" wire:click="delete({{ $row->id }})"><i class="fa fa-trash"></i> </button>
@endcan
