<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase text-center">Adding duration configuration for tax consultant</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label class="control-label">Taxpayer Nationality</label>
                            <select wire:model.defer="nationality" name="nationality" id="nationality" class="form-control">
                                <option  value="">select nationality</option>
                                <option value="1">Local</option>
                                <option value="0">Foreigner</option>

                            </select>
                            @error('nationality')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Category</label>
                            <select wire:model.defer="category" name="category" id="category" class="form-control">
                                <option  value="">select category</option>
                                <option value="Registration Fee">Registration</option>
                                <option value="Renewal Fee">Renewal</option>

                            </select>
                            @error('category')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="control-label">Duration(Years)</label>
                            <select wire:model.defer="duration" class="form-control">
                                <option value="">select duration</option>
                                @for($x=1; $x <= 5; $x++)
                                    <option value="{{ $x }}">{{ $x }} @if($x == 1) year @else years @endif</option>
                                @endfor
                            </select>
                            @error('duration')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
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
                    </div>Save</button>
            </div>
        </div>
    </div>
</div>
