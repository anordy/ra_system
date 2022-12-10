<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Proceeding</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="model-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Date *</label>
                        <input type="date" class="form-control" wire:model.lazy="date" id="date">
                        @error('date')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
        
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Stage *</label>
                        <select wire:model="stage_id" class="form-control" id="stage_id">
                            <option>Choose option</option>
                            @foreach (\App\Models\CaseStage::all() as $option)
                                <option value="{{ $option->id }}">{{ $option->name }}</option>
                            @endforeach
                        </select>
                        @error('stage_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Comment *</label>
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
                        <label class="control-label">Case Decision</label>
                        <select wire:model="decision_id" class="form-control" id="decision_id">
                            <option>Choose option</option>
                            @foreach (\App\Models\CaseDecision::all() as $option)
                                <option value="{{ $option->id }}">{{ $option->name }}</option>
                            @endforeach
                        </select>
                        @error('decision_id')
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
