@if ($row->status == 'active')
    <button class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="right" title="Edit" onclick="Livewire.emit('showModal', 'withholding-agents.edit-responsible-person-modal', '{{ encrypt($row->id) }}')"><i class="fa fa-edit"></i></button>
    <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Deactivate" wire:click="changeStatus('{{ encrypt($row->id) }}')"><i class="fa fa-lock"></i> </button>
@else
    <button class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="right" title="Activate" wire:click="changeStatus('{{ encrypt($row->id) }}')"><i class="fa fa-lock-open"></i> </button>
@endif