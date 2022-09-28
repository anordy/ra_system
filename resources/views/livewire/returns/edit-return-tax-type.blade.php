<div>
    <div class="row">
        <div class="col-md-6 mb-2">
            <label>Name</label>
            <input type="text" class="form-control form-control-lg {{ $errors->first('name') ? 'is-invalid' : '' }}"
                   wire:model="name">
            @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-6 mb-2">
            <label>Code</label>
            <input disabled type="text" class="form-control form-control-lg" wire:model="code">
        </div>
        <div class="col-md-6 mb-2">
            <label>Category</label>
            <input disabled type="text" class="form-control form-control-lg" wire:model="category">
        </div>

        <div class="col-md-6 mb-2">
            <label>GFS Code</label>
            <input type="text" class="form-control form-control-lg {{ $errors->first('gfs_code') ? 'is-invalid' : '' }}"
                   wire:model="gfs_code">
            @error('gfs_code')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-12 d-flex justify-content-end">
            @can('setting-return-tax-type-edit')
                <button type="button" class="btn btn-success px-5" wire:click='update' wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="update">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    Update
                </button>
            @endcan
        </div>

    </div>
</div>