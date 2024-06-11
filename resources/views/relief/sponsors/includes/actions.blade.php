<div class="row">
    <div class="col-12">
        @can('relief-sponsors-edit')
            <button class="btn btn-info btn-sm"
                onclick="Livewire.emit('showModal', 'relief.relief-sponsors-edit-modal','{{ encrypt($value) }}')"><i class="bi bi-pencil-square"></i>
                Edit
            </button>
        @endcan
        @can('relief-sponsors-delete')
            <button class="btn btn-danger btn-sm" wire:click="delete({{$value}})"><i class="bi bi-trash-fill"></i> Delete</button>
        @endcan
    </div>
</div>
