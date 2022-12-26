<div>
    <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'settings.zrb-banks.zrb-bank-account-edit-modal','{{ encrypt($value) }}')"><i class="fa fa-edit"></i> </button>
    <button class="btn btn-danger btn-sm" wire:click="delete('{{ encrypt($value) }}')"><i class="fa fa-trash"></i> </button>
</div>