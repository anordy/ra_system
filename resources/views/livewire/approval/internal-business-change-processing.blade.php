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
                                   value="{{ json_decode($info->old_values)->name ?? 'N/A' }}" disabled>
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

                @if ($info->type === \App\Enum\InternalInfoType::EFFECTIVE_DATE)
                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-6">
                            <label class="control-label">Current Effective Date</label>
                            <input type="text" class="form-control"
                                   value="{{ json_decode($info->old_values)->effective_date ?? 'N/A' }}" disabled>
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">New Effective Date</label>
                            <input type="date" wire:model.defer="newEffectiveDate" name="newEffectiveDate"
                                   value="{{ $newEffectiveDate  }}" id="newEffectiveDate" class="form-control"/>
                            @error('newEffectiveDate')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                @endif

                @if($info->type === \App\Enum\InternalInfoType::TAX_TYPE)
                    @include('livewire.internal-info-change.tax_type.reg_manager_review_tax_type')
                @endif
                    @if($info->type === \App\Enum\InternalInfoType::ELECTRIC)
                        @include('livewire.internal-info-change.electric.registration_manager_review')
                    @endif
                    @if($info->type === \App\Enum\InternalInfoType::LTO)
                        @include('livewire.internal-info-change.lto.registration_manager_review')
                    @endif
                    @if($info->type === \App\Enum\InternalInfoType::CURRENCY)
                        @include('livewire.internal-info-change.currency.registration_manager_review')
                    @endif
                    @if($info->type === \App\Enum\InternalInfoType::TAX_REGION)
                        @include('livewire.internal-info-change.tax_region.registration_manager_review')
                    @endif
            @endif

            @if ($this->checkTransition('director_of_trai_review'))
                @if ($info->type === \App\Enum\InternalInfoType::HOTEL_STARS)
                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-6">
                            <label class="control-label">Current Hotel Star Rating</label>
                            <input type="text" class="form-control"
                                   value="{{ json_decode($info->old_values)->name ?? 'N/A' }}" disabled>
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
                @if ($info->type === \App\Enum\InternalInfoType::EFFECTIVE_DATE)

                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-6">
                            <label class="control-label">Current Effective Date</label>
                            <input type="text" class="form-control"
                                   value="{{ json_decode($info->old_values)->effective_date ?? 'N/A' }}" disabled>
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="control-label">Effective Date</label>
                            <input type="text" disabled
                                   value="{{ \Carbon\Carbon::create($newEffectiveDate)->format('d-M-Y')  }}"
                                   class="form-control"/>
                        </div>
                    </div>

                @endif
                @if($info->type === \App\Enum\InternalInfoType::TAX_TYPE)
                    @include('livewire.internal-info-change.tax_type.director_trai_review_tax_type')
                @endif
                @if($info->type === \App\Enum\InternalInfoType::ELECTRIC)
                    @include('livewire.internal-info-change.electric.director_trai_review')
                @endif
                    @if($info->type === \App\Enum\InternalInfoType::LTO)
                        @include('livewire.internal-info-change.lto.director_trai_review')
                    @endif
                    @if($info->type === \App\Enum\InternalInfoType::CURRENCY)
                        @include('livewire.internal-info-change.currency.director_trai_review')
                    @endif
                    @if($info->type === \App\Enum\InternalInfoType::TAX_REGION)
                        @include('livewire.internal-info-change.tax_region.director_trai_review')
                    @endif
            @endif

            @include('livewire.approval.transitions')

            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Comments</label>
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

        @if ($this->checkTransition('director_of_trai_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-danger"
                        wire:click="confirmPopUpModal('reject', 'director_of_trai_reject')">Reject & Return
                </button>
                <button type="button" class="btn btn-primary"
                        wire:click="confirmPopUpModal('approve', 'director_of_trai_review')">Approve & Complete
                </button>
            </div>
        @elseif ($this->checkTransition('registration_manager_review'))
            <div class="modal-footer p-2 m-0">
                <button type="button" class="btn btn-primary"
                        wire:click="confirmPopUpModal('approve', 'registration_manager_review')">Approve & Forward
                </button>
            </div>
        @endif
    </div>
@endif
