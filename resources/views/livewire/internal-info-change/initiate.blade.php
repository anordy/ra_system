<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Initiate Internal Information Change</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Select Information Type</label>
                        <select type="text" class="form-control" wire:model.lazy="informationType"
                            id="informationType">
                            <option>-------- Select Information Type -------</option>
                            <option value="hotelStars">Hotel Stars</option>
                        </select>
                        @error('informationType')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                   
                </div>

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-8">
                        <label class="control-label">Enter Business Location ZIN Number</label>
                        <input type="text" class="form-control" wire:model.defer="zin" id="zin">
                        @error('zin')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-4">
                        <button type="button" wire:click="getZin()" class="btn btn-secondary mt-4">Search</button>
                    </div>
                </div>

                @if ($businessHotel)
                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-6">
                            <label class="control-label">Current Hotel Star Rating</label>
                            <input type="text" class="form-control" value="{{ $businessHotel->star->no_of_stars }}" disabled>
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">New Hotel Star Rating</label>
                            <select type="text" class="form-control" wire:model.lazy="newHotelStarId"
                                id="newHotelStarId">
                                <option value="">--------- N/A ---------</option>
                                @foreach ($hotelStars as $hotelStar)
                                    <option value="{{ $hotelStar->id }}">{{ $hotelStar->no_of_stars }} Star</option>
                                @endforeach
                            </select>
                            @error('newHotelStarId')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endif

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
