<button class="btn btn-info btn-sm"
        onclick="Livewire.emit('showModal', 'mvr.generic-setting-add-modal', '{{ $model }}', '{{ encrypt($value) }}')">
    <i class="fa fa-edit"></i>
</button>
<button class="btn btn-danger btn-sm" wire:click="delete($value)">
    <i class="fa fa-trash"></i>
</button>
