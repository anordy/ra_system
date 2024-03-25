@if(approvalLevel(Auth::user()->level_id, 'Maker'))
    <div>
        <button class="btn btn-info btn-sm"
                onclick="Livewire.emit('showModal', 'settings.penalty-rate.penalty-rate-edit-modal','{{ encrypt($value) }}')">
            <i class="bi bi-pencil-square"></i></button>
        <button class="btn btn-danger btn-sm" wire:click="delete('{{ encrypt($value) }}')"><i class="bi bi-trash-fill"></i>
        </button>
    </div>
@endif