{{--<button onclick="Livewire.emit('showModal', 'bank-edit-modal', '{{ encrypt($value) }}')" class="btn btn-primary btn-sm">--}}
{{--    <i class="bi bi-pencil-square mr-1"></i>--}}
{{--    Edit--}}
{{--</button>--}}

<button wire:click="delete('{{encrypt($value)}}')" class="btn btn-danger btn-sm">
    <i class="bi bi-trash-fill mr-1"></i>
    Delete
</button>