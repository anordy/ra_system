@if ($row->status == 'active')
    <button class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="right" title="Edit" onclick="Livewire.emit('showModal', 'withholding-agents.edit-responsible-person-modal', '{{ encrypt($row->id) }}')"><i class="bi bi-pencil-square"></i></button>
    <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Deactivate" wire:click="changeStatus('{{ encrypt($row->id) }}')"><i class="bi bi-lock-fill"></i> </button>
@else
    <button class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="right" title="Activate" wire:click="changeStatus('{{ encrypt($row->id) }}')"><i class="bi bi-unlock-fill"></i> </button>
@endif