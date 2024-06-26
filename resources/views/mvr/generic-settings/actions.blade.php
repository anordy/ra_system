<button class="btn btn-primary btn-sm"
        onclick="Livewire.emit('showModal', 'mvr.generic-setting-add-modal', '{{ $model }}', '{{ encrypt($value) }}')">
    <i class="bi bi-pencil-fill mr-1"></i> Edit
</button>
<button class="btn btn-danger btn-sm" wire:click="delete('{{ $value }}')">
    <i class="bi bi-trash3-fill mr-1"></i> Delete
</button>
