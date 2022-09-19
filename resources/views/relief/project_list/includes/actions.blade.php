<div class="row">
    <div class="col-12">
        @can('relief-projects-list-edit')
            <button class="btn btn-info btn-sm"
                onclick="Livewire.emit('showModal', 'relief.relief-project-list-edit-modal',{{$value}})"><i class="fa fa-edit"></i>
            </button>
        @endcan
        @can('relief-projects-list-delete')
            <button class="btn btn-danger btn-sm" wire:click="delete({{$value}})"><i class="fa fa-trash"></i> </button>
        @endcan
    </div>
</div>
