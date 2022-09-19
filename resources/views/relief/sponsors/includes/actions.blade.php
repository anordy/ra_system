<div class="row">
    <div class="col-12">
        @can('relief-sponsors-edit')
            <button class="btn btn-info btn-sm"
                onclick="Livewire.emit('showModal', 'relief.relief-sponsors-edit-modal',{{$value}})"><i class="fa fa-edit"></i>
                Edit
            </button>
        @endcan
        @can('relief-sponsors-delete')
            <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="fa fa-trash"></i> Delete</button>
        @endcan
    </div>
</div>
