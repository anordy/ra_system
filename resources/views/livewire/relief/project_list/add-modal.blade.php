<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Add Project List</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Name</label>
                        <input type="text" class="form-control" wire:model.lazy="name" id="name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Description</label>
                        <input type="text" class="form-control" wire:model.lazy="description" id="description">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Rate (%)</label>
                        <input type="number" class="form-control" wire:model.lazy="rate" id="rate">
                        @error('rate')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-12">
                        <label class="">Ministry </label>
                        <select class="form-control" wire:model.lazy="ministry_id">
                            <option value='null' disabled selected>Choose option</option>
                            @foreach ($ministries as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        @error('ministry_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-12">
                        <label class="">Sponsor </label>
                        <select class="form-control" wire:model.lazy="relief_sponsor_id">
                            <option value='null' selected>Choose option</option>
                            @foreach ($sponsors as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        @error('relief_sponsor_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    

                    <div class="form-group col-lg-12">
                        <label class="control-label">Government Notice</label>
                        <input type="file" class="form-control" accept="application/pdf" 
                            wire:model.lazy="government_notice_path" id="government_notice_path">
                        @error('government_notice_path')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <div class="mt-1" wire:loading wire:target="government_notice_path">
                            <i class="spinner-border spinner-border-sm mr-2" role="status"></i>
                            Uploading
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
                    </div>Save changes</button>
            </div>
        </div>
    </div>
</div>
