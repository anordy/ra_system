<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase text-center">Adding fee configuration for taxagent</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label class="control-label">Category</label>
                            <select wire:model.lazy="category" name="category" id="category" class="form-control">
                                <option  value="">select category</option>
                                <option value="first fee">first fee</option>
                                <option value="renewal fee">renewal fee</option>

                            </select>
                            @error('category')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        @if($category == 'renewal fee')
                        <div class="form-group col-lg-6">
                            <label class="control-label">Duration</label>
                            <select wire:model="duration" class="form-control">
                                <option value="">select duration</option>
                                <option value="yearly">Yearly</option>
                                <option value="monthly">Monthly</option>
                                <option value="daily">Daily</option>
                            </select>
                            @error('duration')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif

                        @if($duration)
                            <div class="form-group col-lg-6">
                                <label class="control-label">No of days/months/years</label>
                                <input placeholder="e.g 10" type="text" class="form-control" wire:model.lazy="no_of_days" id="no_of_days">
                                @error('no_of_days')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <div class="form-group col-lg-6">
                            <label class="control-label">Amount</label>
                            <input type="text" class="form-control" wire:model.lazy="amount" id="amount">
                            @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
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
