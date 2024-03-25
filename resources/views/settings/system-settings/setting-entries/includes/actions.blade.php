<div>
    @if ($row->is_approved == 1)
        @if (approvalLevel(Auth::user()->level_id, 'Maker'))
            <button class="btn btn-info btn-sm"
                onclick="Livewire.emit('showModal', 'settings.system-settings.system-setting-edit-modal','{{ encrypt($row->id) }}')"><i
                    class="bi bi-pencil-square"></i> </button>
            <button class="btn btn-danger btn-sm" wire:click="delete('{{ encrypt($row->id) }}')"><i class="bi bi-trash-fill"></i>
            </button>
        @endif
    @endif
</div>
