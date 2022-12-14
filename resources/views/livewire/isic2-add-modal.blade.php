<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">ISIC LEVEL 2</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group col-lg-12">
                    <label class="">ISIC Level 1 </label>
                    <select class="form-control" wire:model.lazy="isic1_id">
                        <option value='null' disabled selected>Choose option</option>
                        @foreach ($isic1s as $row)
                            <option value="{{ $row->id }}">{{ $row->description }}</option>
                        @endforeach
                    </select>
                    @error('isic1_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Code</label>
                        <input type="text" class="form-control" wire:model.lazy="code" id="code">
                        @error('code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Description</label>
                        <input type="text" class="form-control" wire:model.lazy="description" id="description">
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Save changes</button>
            </div>
        </div>
    </div>
</div>
