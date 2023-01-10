<div>
    @if ($row->is_approved == 1)
        @if (approvalLevel(Auth::user()->level_id, 'Maker'))
            <button class="btn btn-info btn-sm"
                onclick="Livewire.emit('showModal', 'settings.zrb-banks.zrb-bank-account-edit-modal','{{ encrypt($row->id) }}')"><i
                    class="fa fa-edit"></i> </button>
            <button class="btn btn-danger btn-sm" wire:click="delete('{{ encrypt($row->id) }}')"><i class="fa fa-trash"></i>
            </button>
        @endif
    @endif
</div>
