<div>
    @if($checkFiling)
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-capitalize text-center">
                        upgrading {{$this->return['taxtype']['name']}} tax type to
                        @if($this->return['taxtype']['code'] == \App\Models\TaxType::LUMPSUM_PAYMENT) Stamp Duty Tax Type  @else VAT Tax Type @endif for {{$this->return['business']['name']}} business</h6>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                                class="fa fa-times-circle"></i></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        This business has not completed filing all of its returns!!!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    @else
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-capitalize text-center">
                        upgrading {{$this->return['taxtype']['name']}} tax type to
                        @if($this->return['taxtype']['code'] == \App\Models\TaxType::LUMPSUM_PAYMENT) Stamp Duty Tax Type  @else VAT Tax Type @endif for {{$this->return['business']['name']}} business</h6>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                                class="fa fa-times-circle"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-12">
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
                        <div class="form-group col-lg-12">
                            <label class="control-label">Effective Date</label>
                            <input min="{{$min}}" max="{{$max}}" type="date" wire:model.lazy="effective_date" name="effective_date" id="effective_date" class="form-control" />
                            @error('effective_date')
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
    @endif
</div>
