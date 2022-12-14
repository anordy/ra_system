<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase text-center">Adding financial year</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-md-12 col-lg-6">
                        <label class="control-label">Year</label>
                        <select wire:model.lazy="year" name="year"  class="form-control">
                            <option  value="">select years</option>
                            <option value="{{date('Y')}}">{{date('Y')}}</option>
                            @for ($x=1; $x <= 3; $x++)
                                <option value="{{date('Y') - $x}}">{{date('Y') - $x}}</option>
                            @endfor
                        </select>
                        @error('year')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-12 col-lg-6">
                        <label class="control-label">Name</label>
                        <input type="text" value="{{$name}}" name="code" wire:model.lazy="name" class="form-control" readonly>
                        @error('name')
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
                    </div>Save</button>
            </div>
        </div>
    </div>
</div>
