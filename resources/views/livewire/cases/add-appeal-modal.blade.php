<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Add Appeal</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="model-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Appeal Number</label>
                        <input type="text" class="form-control" wire:model.lazy="appeal_number" id="appeal_number">
                        @error('appeal_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Filing Date</label>
                        <input type="date" class="form-control" wire:model.lazy="date" id="date">
                        @error('date')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Appeal Details</label>
                        <textarea class="form-control @error('comment') is-invalid @enderror" wire:model='comment' rows="3"></textarea>
                        @error('comment')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Appeal Level</label>
                        <select wire:model="court_level_id" class="form-control" id="court_level_id">
                            <option>Choose option</option>
                            @foreach (\App\Models\CourtLevel::all() as $option)
                                <option value="{{ $option->id }}">{{ $option->name }}</option>
                            @endforeach
                        </select>
                        @error('court_level_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button wire:click="submit" wire:loading.attr="disabled" class="btn btn-primary">
                    <div wire:loading wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Submit
                </button>
            </div>
        </div>
    </div>
</div>
