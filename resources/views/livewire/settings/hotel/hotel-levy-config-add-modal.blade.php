<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Add Hotel Levy Configuration</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
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
                </div>
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Code</label>
                        <input type="text" class="form-control" wire:model.lazy="code" id="code">
                        @error('code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row pr-3 ml-4">
                    <div class="form-group col-lg-12">
                        <input type="checkbox" class="form-check-input" wire:model.lazy="is_rate_charged" id="is_rate_charged">
                        <label class="form-check-label">Is Rate Charged</label>
                        @error('is_rate_charged')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                @if ($is_rate_charged === true)
                <div class="row pr-3 ml-4">
                    <div class="form-group col-lg-12">
                        <input type="checkbox" class="form-check-input" wire:model.lazy="is_rate_in_percentage" id="is_rate_in_percentage">
                        <label class="form-check-label">Is Rate in Percentage (%)</label>
                        @error('is_rate_in_percentage')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                @if ($is_rate_in_percentage === true)
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Rate in Percentage (%)</label>
                        <input type="text" class="form-control" wire:model.lazy="rate_in_percentage" id="rate_in_percentage">
                        @error('rate_in_percentage')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                @endif

                @if ($is_rate_in_percentage === false)
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Rate in Amount</label>
                        <input type="text" class="form-control" wire:model.lazy="rate_in_amount" id="rate_in_amount">
                        @error('rate_in_amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                @endif
                @endif
     
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Status</label>
                        <select type="text" class="form-control" wire:model.lazy="status" id="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Financial Year</label>
                        <select type="text" class="form-control" wire:model.lazy="financial_year" id="financial_year">
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                        </select>
                        @error('financial_year')
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
                    </div>Save changes</button>
            </div>
        </div>
    </div>
</div>
