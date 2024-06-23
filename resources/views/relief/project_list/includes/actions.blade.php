<div class="row">
    <div class="col-12">
        @can('relief-projects-list-edit')
            <button class="btn btn-info btn-sm"
                onclick="Livewire.emit('showModal', 'relief.relief-project-list-edit-modal','{{encrypt($value)}}')"><i class="bi bi-pencil-square"></i>
            </button>
        @endcan
        @can('relief-projects-list-delete')
            <button class="btn btn-danger btn-sm" wire:click="delete({{$value}})"><i class="bi bi-trash-fill"></i> </button>
        @endcan
    </div>
</div>
