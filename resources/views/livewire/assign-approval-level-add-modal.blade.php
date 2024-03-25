<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Add Approval Level to this role</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="">Level</label>
                        <select class="form-control" wire:model.defer="level">
                            <option value="">Choose option</option>
                            @foreach ($levels as $row)
                                <option @if(!empty($role_level) && $role_level->approval_level_id == $row->id) selected @endif value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        @error('level')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Assign</button>
            </div>
        </div>
    </div>
</div>
