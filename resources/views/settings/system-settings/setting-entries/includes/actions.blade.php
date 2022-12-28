<div>
    @if ($row->is_approved == 1 || $row->is_approved == 2)
        <button class="btn btn-info btn-sm"
            onclick="Livewire.emit('showModal', 'settings.system-settings.system-setting-edit-modal','{{ encrypt($row->id) }}')"><i
                class="fa fa-edit"></i> </button>
        <button class="btn btn-danger btn-sm" wire:click="delete('{{ encrypt($row->id) }}')"><i class="fa fa-trash"></i>
        </button>
    @endif
</div>
