<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Change User Password</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label class="control-label">Password</label>
                            <input type="password" class="form-control" wire:model="password" id="password">
                            <div class="progress" style="height: 8px !important;">
                                <div class="progress-bar  progress-bar-animated bg-success" role="progressbar" aria-valuenow="{{ $passwordStrength }}" style="width: {{ $passwordStrength }}%;" aria-valuemin="0" aria-valuemax="4"></div>
                              </div>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Confirm Password</label>
                            <input type="password" class="form-control" wire:model="password_confirmation" id="password_confirmation">
                        </div>
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
                    </div>Update changes</button>
            </div>
        </div>
    </div>
</div>
