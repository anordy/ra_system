@if($checkFiling)
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-capitalize text-center">
                    upgrading {{$this->return['taxtype']['name']}} tax type to
                    @if($this->return['taxtype']['code'] == \App\Models\TaxType::LUMPSUM_PAYMENT)
                        Stamp Duty Tax Type
                    @else
                        VAT Tax Type
                    @endif for {{$this->return['business']['name']}} business</h6>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    This business has not completed filing all of its returns
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-header">
            <h6 class="card-title text-capitalize text-center">
                upgrading {{$this->return['taxtype']['name']}} tax type to
                @if($this->return['taxtype']['code'] == \App\Models\TaxType::LUMPSUM_PAYMENT)
                    Stamp Duty Tax Type
                @else
                    VAT Tax Type
                @endif for {{$this->return['business']['name']}} business</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-lg-4">
                    <label class="control-label">Currency</label>
                    <select wire:model.defer="currency" name="currency" id="currency" class="form-control">
                        <option value="">Select currency</option>
                        @foreach($currencies as $currency)
                            <option value="{{$currency->iso}}">{{$currency->name}}</option>
                        @endforeach
                    </select>
                    @error('currency')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-lg-4">
                    <label class="control-label">Effective Date</label>
                    <input min="{{$min}}" max="{{$max}}" type="date" wire:model.defer="effective_date"
                           name="effective_date" id="effective_date" class="form-control"/>
                    @error('effective_date')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                @if($this->return['taxtype']['code'] == \App\Models\TaxType::STAMP_DUTY)
                    <div class="form-group col-lg-4">
                        <label class="control-label">Sub Vat Category</label>
                        <select wire:model.defer="sub_vat_id" name="sub_vat_id" id="sub_vat_id" class="form-control">
                            <option value="">Select sub vat category</option>
                            @foreach($subVats as $subVat)
                                <option value="{{$subVat->id}}">{{$subVat->name}}</option>
                            @endforeach
                        </select>
                        @error('sub_vat_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                <div class="col-md-12 mb-12">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Recommendations and Grounds</label>
                        <textarea class="form-control @error('comments') is-invalid @enderror"
                                  wire:model.defer='comments' rows="3"></textarea>
                        @error('comments')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'registration_officer_review')" wire:loading.attr="disabled">
                <div wire:loading.delay wire:target="confirmPopUpModal('approve', 'registration_officer_review')">
                    <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                Approve & Forward
            </button>
        </div>
    </div>
@endif