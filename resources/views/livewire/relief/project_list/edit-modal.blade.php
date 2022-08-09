<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Edit Project</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Name</label>
                        <input type="text" class="form-control" wire:model.lazy="name" id="name"
                            @error('name') is-invalid @enderror>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label" for="Description">Description</label>
                        <input type="text" class="form-control" wire:model.lazy="description" id="Description">
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label" for="Rate">Rate</label>
                        <input class="form-control @error('rate') is-invalid @enderror" wire:model.lazy="rate"
                            type="number" min="0" max="100" />
                        @error('rate')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Update changes</button>
            </div>
        </div>
    </div>
</div>
