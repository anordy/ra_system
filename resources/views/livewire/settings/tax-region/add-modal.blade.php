<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-uppercase">Add Tax Region</h6>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="font-weight-bold">Prefix</label>
                        <input type="text" class="form-control" wire:model.lazy="prefix" placeholder="With 2 digits, E.g. 04">
                        @error('prefix')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="font-weight-bold">Name</label>
                        <input type="text" class="form-control" wire:model.lazy="name">
                        @error('name')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="font-weight-bold">Location</label>
                        <select wire:model="location" class="form-control">
                            <option value="">Please choose location.</option>
                            <option value="{{ \App\Models\Region::UNGUJA }}">Unguja</option>
                            <option value="{{ \App\Models\Region::PEMBA }}">Pemba</option>
                        </select>
                        @error('location')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Save changes</button>
            </div>
        </div>
    </div>
</div>
