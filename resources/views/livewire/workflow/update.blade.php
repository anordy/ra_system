<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Update Place</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-6">
                        <label class="control-label">Place Name</label>
                        <label for="" class="form-control">{{ ucfirst(str_replace('_', ' ', $name)) }}</label>
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Place Owner</label>
                        <label for=""
                            class="form-control">{{ ucfirst(str_replace('_', ' ', $owner)) }}</label>
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="">Operator Type </label>
                        <select class="form-control" wire:model.lazy="operator_type">
                            <option value="" disabled selected>Choose option</option>
                            <option value="user">User</option>
                            <option value="role">Role</option>
                        </select>
                    </div>
                    @if ($operator_type == 'user')
                        <div class="form-group col-lg-6">
                            <label class="">Users </label>
                            <select class="form-control min-height-250" wire:model.defer="user_id" multiple >
                                <option value="" disabled selected>Choose option</option>
                                @foreach ($users as $row)
                                    <option value="{{ $row->id }}">{{ $row->full_name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                    @if ($operator_type == 'role')
                        <div class="form-group col-lg-6">
                            <label class="">Roles </label>
                            <select class="form-control" wire:model.defer="role_id" multiple >
                                <option value="" disabled selected>Choose option</option>
                                @foreach ($roles as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Save changes</button>
            </div>
        </div>
    </div>
</div>
