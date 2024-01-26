@if ($this->checkTransition('registration_manager_review'))
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

        @if($taxTypeChange)
            <div class="card-body">
                <div class="row m-2 pt-3">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Tax Type Currency</span>
                        <p class="my-1">{{ $taxTypeChange->to_tax_type_currency ?? '' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Effective Date</span>
                        <p class="my-1">{{ \Carbon\Carbon::create($taxTypeChange->effective_date)->format('d M Y') ?? '' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Sub Vat Category</span>
                        <p class="my-1">{{ $taxTypeChange->subvat->name ?? '' }}</p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <span class="font-weight-bold text-uppercase">Recommendations/Grounds</span>
                        <p class="my-1">{{ $taxTypeChange->reason ?? '' }}</p>
                    </div>
                </div>

            </div>
        @else
            <span>No Information Found</span>
        @endif
    </div>

    <div class="row mt-2 px-3">
        <div class="col-md-12 mb-3">
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Comments</label>
                <textarea class="form-control @error('comment') is-invalid @enderror" wire:model.defer='comment'
                          rows="3"></textarea>
                @error('comment')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>
    <div class="modal-footer p-2 m-0">
        <button type="button" class="btn btn-danger"
                wire:click="confirmPopUpModal('reject', 'registration_manager_reject')">Reject & Return
        </button>
        <button type="button" class="btn btn-primary"
                wire:click="confirmPopUpModal('approve', 'registration_manager_review')">Approve & Complete
        </button>
    </div>
@endif