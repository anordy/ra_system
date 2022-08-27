<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Assign Officer</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="model-body">

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Choose Officer</label>
                        <select wire:model="user_id" class="form-control" id="user_id">
                            <option>Choose Officer</option>
                            @foreach (\App\Models\User::all() as $option)
                                <option value="{{ $option->id }}">{{ $option->fullname() }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button wire:click="submit" wire:loading.attr="disabled" class="btn btn-primary">
                    <div wire:loading wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Submit
                </button>
            </div>
        </div>
    </div>
</div>
