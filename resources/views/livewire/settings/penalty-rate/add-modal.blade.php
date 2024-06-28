<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Add Penalty Rate</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Financial Year</label>
                        <select type="text" class="form-control" wire:model.lazy="financial_year_id" id="financial_year_id">
                            @foreach ($financialYears as $financialYear)
                                <option value="{{ $financialYear->id }}">{{ $financialYear->code }}</option>
                            @endforeach
                        </select>
                        @error('financial_year_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    @foreach ($configs as $key => $config)
                            <div class="col-md-6 form-group">
                                <label class="return-label">{{ $config['name'] }}</label>
                                <input type="number" wire:model.lazy="configs.{{ $key }}.rate" required
                                    class="form-control @error('configs.' . $key . '.rate') is-invalid @enderror">
                                @error('configs.' . $key . '.rate')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Save changes
                </button>
            </div>
        </div>
    </div>
</div>
