<div class="row">
    <div class="col-12">
        @can('relief-projects-edit')
            <button class="btn btn-info btn-sm"
                onclick="Livewire.emit('showModal', 'relief.relief-project-edit-modal','{{encrypt($value)}}')"><i class="fa fa-edit"></i>
            </button>
        @endcan
        @can('relief-projects-delete')
            <button class="btn btn-danger btn-sm" wire:click="delete('{{encrypt($value)}}')"><i class="fa fa-trash"></i> </button>
        @endcan
    </div>
</div>
