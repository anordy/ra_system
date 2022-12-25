<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content" x-data="{password:'',password_confirm:''}">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Add User</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label class="control-label" for="f_name">First Name</label>
                            <input type="text" class="form-control" wire:model.defer="fname" id="f_name" autocomplete="given-name">
                            @error('fname')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label" for="l_name">Last Name</label>
                            <input type="text" class="form-control" wire:model.defer="lname" id="l_name" autocomplete="family-name">
                            @error('lname')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Email</label>
                            <input type="email" class="form-control" wire:model.defer="email" id="email">
                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Phone Number</label>
                            <input type="phone" class="form-control" wire:model.defer="phone" id="phone">
                            @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="">Gender </label>
                            <select class="form-control" wire:model.defer="gender">
                                <option value="" disabled selected>Choose option</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                            @error('gender')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="">Role </label>
                            <select class="form-control" wire:model.defer="role">
                                <option value="" disabled selected>Choose option</option>
                                @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Password</label>
                            <input type="password" class="form-control" wire:model.defer="password" id="password" x-model="password">
                            @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Comfirm Password</label>
                            <input type="password" class="form-control" wire:model.defer="password_confirmation"
                                id="password_confirmation" x-model="password_confirm">
                        </div>

                        <div class="form-group col-md-12 my-0">
                            <small x-bind:class="password.length >=8 ? 'text-success':'text-danger'"><i class="pr-2 bi" x-bind:class="password.length >=8 ? 'bi-check-circle-fill':'bi-x-circle-fill'"></i>Password must contain At least 8 characters</small>
                        </div>
                        <div class="form-group col-md-12 my-0">
                            <small x-bind:class="password.match(/[A-Z]/) ? 'text-success':'text-danger'"><i class="pr-2 bi" x-bind:class="password.match(/[A-Z]/) ? 'bi-check-circle-fill':'bi-x-circle-fill'"></i>Password must contain uppercase</small>
                        </div>
                        <div class="form-group col-md-12 my-0">
                            <small x-bind:class="password.match(/[!@$#%^&*(),.?:{}|<>]/) ? 'text-success':'text-danger'"><i class="pr-2 bi" x-bind:class="password.match(/[!@$#%^&*(),.?:{}|<>]/) ? 'bi-check-circle-fill':'bi-x-circle-fill'"></i>Password must contain special character</small>
                        </div>
                        <div class="form-group col-md-12 my-0">
                            <small x-bind:class="password.match(/[0-9]/) ? 'text-success':'text-danger'"><i class="pr-2 bi" x-bind:class="password.match(/[0-9]/) ? 'bi-check-circle-fill':'bi-x-circle-fill'"></i>Password must contain Number</small>
                        </div>
                        <div class="form-group col-md-12 my-0">
                            <small x-bind:class="password.length >=8 && password==password_confirm ? 'text-success':'text-danger'"><i class="pr-2 bi" x-bind:class="password.length >=8 & password==password_confirm ? 'bi-check-circle-fill':'bi-x-circle-fill'"></i>Passwords must match</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" x-bind:disabled="!(password.length >=8 && password.match(/[A-Z]/) && password.match(/[!@$#%^&*(),.?:{}|<>]/) && password.match(/[0-9]/) && password==password_confirm)" wire:click='submit' wire:loading.attr="disabled"><div wire:loading.delay wire:target="submit">
                    <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>Save changes</button>
            </div>
        </div>
    </div>
</div>