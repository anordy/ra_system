@if (count($this->getEnabledTranstions()) >= 1)
    <div class="card shadow-sm mb-2 bg-white rounded-0 m-2 pt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Internal Information Change Approval
        </div>
        <div class="card-body p-4">
            @if ($this->checkTransition('registration_manager_review'))
                @if ($info->type === \App\Enum\InternalInfoType::HOTEL_STARS)
                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-6">
                            <label class="control-label">Current Hotel Star Rating</label>
                            <input type="text" class="form-control"
                                value="{{ json_decode($info->old_values)->no_of_stars ?? 'N/A' }}" disabled>
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">New Hotel Star Rating</label>
                            <select type="text" class="form-control" wire:model.lazy="newHotelStar"
                                id="newHotelStar">
                                <option value="">--------- N/A ---------</option>
                                @foreach ($hotelStars as $hotelStar)
                                    <option value="{{ $hotelStar->id }}">{{ $hotelStar->name }} Star</option>
                                @endforeach
                            </select>
                            @error('newHotelStar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endif
            @endif

            @if ($this->checkTransition('director_of_trai_review'))
                @if ($info->type === \App\Enum\InternalInfoType::HOTEL_STARS)
                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-6">
                            <label class="control-label">Current Hotel Star Rating</label>
                            <input type="text" class="form-control"
                                value="{{ json_decode($info->old_values)->no_of_stars ?? 'N/A' }}" disabled>
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">New Hotel Star Rating</label>
                            <select type="text" class="form-control" disabled wire:model.lazy="newHotelStar"
                                id="newHotelStar">
                                <option value="">--------- N/A ---------</option>
                                @foreach ($hotelStars as $hotelStar)
                                    <option value="{{ $hotelStar->id }}">{{ $hotelStar->name }}</option>
                                @endforeach
                            </select>
                            @error('newHotelStar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endif
            @endif

            @include('livewire.approval.transitions')

            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Comments</label>
                        <textarea class="form-control @error('comments') is-invalid @enderror" wire:model.defer='comments' rows="3"></textarea>
                        @error('comments')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        @if ($this->checkTransition('director_of_trai_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject', 'director_of_trai_reject')">Reject & Return</button>
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'director_of_trai_review')">Approve & Complete</button>
            </div>
        @elseif ($this->checkTransition('registration_manager_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary"
                    wire:click="confirmPopUpModal('approve', 'registration_manager_review')">Approve & Forward</button>
            </div>
        @endif
    </div>
@endif
