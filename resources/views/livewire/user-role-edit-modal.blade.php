<div>
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Edit User Role</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    {{-- <div class="row"> --}}
                        {{-- <div class="form-group col-lg-6">
                            <label class="control-label">First Name</label>
                            <input type="text" class="form-control" wire:model.lazy="fname" id="fnname">
                            @error('fname')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Last Name</label>
                            <input type="text" class="form-control" wire:model.lazy="lname" id="lame">
                            @error('lname')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Email</label>
                            <input type="email" class="form-control" wire:model.lazy="email" id="email">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Phone Number</label>
                            <input type="phone" class="form-control" wire:model.lazy="phone" id="phone">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="">Gender </label>
                            <select class="form-control" wire:model.lazy="gender">
                                <option value="" disabled selected>Choose option</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                            @error('gender')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div> --}}
                        <div class="form-group col-12">
                            <label class="">Role </label>
                            <select class="form-control" wire:model.lazy="role">
                                <option value="" disabled selected>Choose option</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    {{-- </div> --}}


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Update changes</button>
            </div>
        </div>
    </div>
</div>
