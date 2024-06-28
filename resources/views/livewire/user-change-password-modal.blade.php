<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content" x-data="{password:'', password_confirm: ''}">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Change User Password</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>
                    <div class="col-md-6">
                        <input x-model="password" type="password" wire:model.lazy="password"
                            class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Confirm
                        Password</label>

                    <div class="col-md-6">
                        <input type="password" wire:model.lazy="password_confirmation" id="password_confirmation"
                            x-model="password_confirm"
                            class="form-control @error('password_confirmation') is-invalid @enderror">
                        @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-sm-4 px-3">
                        <div class="m-0">
                            <span x-bind:class="password.length >= 8 ? 'text-success':'text-danger'">
                                <i class=" pr-2"
                                    x-bind:class="password.length >=8 ? 'bi bi-check-circle-fill':'bi bi-x-circle-fill'"></i>
                                <span>At least 8 characters required</span>
                            </span>
                        </div>
                        <div class="m-0">
                            <span x-bind:class="password.match(/[a-z]/) ? 'text-success':'text-danger'">
                                <i class=" pr-2"
                                    x-bind:class="password.match(/[a-z]+/) ? 'bi bi-check-circle-fill':'bi bi-x-circle-fill'"></i>
                                <span> Must contain Lowercase</span>
                            </span>
                        </div>
                        <div class="m-0">
                            <span x-bind:class="password.match(/[A-Z]/) ? 'text-success':'text-danger'">
                                <i class=" pr-2"
                                    x-bind:class="password.match(/[A-Z]+/) ? 'bi bi-check-circle-fill':'bi bi-x-circle-fill'"></i>
                                <span> Must contain Uppercase</span>
                            </span>
                        </div>
                        <div class="m-0">
                            <span :class="password.match(/[0-9]/) ? 'text-success':'text-danger'">
                                <i class=" pr-2"
                                    x-bind:class="password.match(/[0-9]+/) ? 'bi bi-check-circle-fill':'bi bi-x-circle-fill'"></i>
                                <span> Must contain a number</span>
                            </span>
                        </div>
                        <div class="m-0">
                            <span x-bind:class="password.match(/[!@#$%^&*(),.?:{}|<>]/) ? 'text-success':'text-danger'">
                                <i class=" pr-2"
                                    x-bind:class="password.match(/[!@$%^&*(),.?:{}|<>]/) ? 'bi bi-check-circle-fill':'bi bi-x-circle-fill'"></i>
                                <span> Must contain Special character</span>
                                </strong>
                        </div>
                        <div class="m-0">
                            <span
                                x-bind:class="password.length>=8 && password == password_confirm ? 'text-success':'text-danger'">
                                <i class=" pr-2"
                                    x-bind:class="password.length >= 8 && password == password_confirm ? 'bi bi-check-circle-fill':'bi bi-x-circle-fill'"></i>
                                <span> Passwords must match</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled"
                    x-bind:disabled="!(password.length >=8 && password == password_confirm && password.match(/[!@$%^&*(),.?:{}|<>]/) && password.match(/[A-Z]+/)) && password.match(/[a-z]+/)) && password.match(/[0-9]/)">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Update changes
                </button>
            </div>
        </div>
    </div>
</div>