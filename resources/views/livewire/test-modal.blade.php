<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="btn btn-danger btn-sm"  wire:click='hideModal'><i class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Name</label>
                        <input type="text" class="form-control" wire:model.lazy="name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
    
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click='hideModal'>Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Save changes</button>
            </div>
        </div>
    </div>
</div>
