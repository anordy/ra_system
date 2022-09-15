<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase text-center">Adding fee configuration for taxagent</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label class="control-label">Currency</label>
                        <select wire:model.lazy="currency" name="currency" id="currency" class="form-control">
                            <option  value="">select currency</option>
                            @foreach($currencies as $currency)
                                <option value="{{$currency->iso}}">{{$currency->name}}</option>
                            @endforeach
                        </select>
                        @error('currency')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Effective Date</label>
                        <input type="date" wire:model.lazy="effective_date" name="effective_date" id="effective_date" class="form-control" />
                        @error('effective_date')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-12">
                        <label class="control-label">Comment</label>
                        <textarea wire:model.lazy="comment" name="comment" id="comment" class="form-control" ></textarea>
                        @error('comment')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Save</button>
            </div>
        </div>
    </div>
</div>
