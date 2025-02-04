<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="text-uppercase">Initiate Blacklist</h5>
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
        </div>
        <div class="modal-body">
            <div class="border-0">
                @include('layouts.component.messages')

                <div class="row mx-4">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>Blacklist Type *</label>
                            <select wire:model="blackListType"
                                    @if($initiatorType === \App\Enum\Mvr\MvrBlacklistInitiatorType::ZARTSA) disabled
                                    @endif
                                    class="form-control @error('blackListType') is-invalid @enderror">
                                @if($initiatorType === \App\Enum\Mvr\MvrBlacklistInitiatorType::ZARTSA)
                                    <option selected disabled value="{{ \App\Enum\Mvr\MvrBlacklistType::DL }}">Drivers
                                        License
                                    </option>
                                @else
                                    <option value="">Select Option</option>
                                    <option value="{{ \App\Enum\Mvr\MvrBlacklistType::MVR }}">Motor Vehicle</option>
                                    <option value="{{ \App\Enum\Mvr\MvrBlacklistType::DL }}">Drivers License</option>
                                @endif

                            </select>
                            @error('blackListType')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    @if($blackListType)
                        <div class="col-md-6">
                            <label>{{ formatEnum($blackListType)  }} Number</label>
                            <div class="input-group mb-3">
                                <input type="text" wire:model.defer="blackListNumber"
                                       class="form-control @error('blackListNumber') is-invalid @enderror"
                                       placeholder="Enter {{ formatEnum($blackListType) }} Number">
                                <div class="input-group-append">
                                    <button class="btn btn-info" type="button" wire:click="searchBlacklist()">
                                        <i class="bi bi-search mr-1" wire:loading.remove
                                           wire:target="searchBlacklist"></i>
                                        <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                                           wire:target="searchBlacklist"></i>
                                        Search
                                    </button>
                                </div>
                            </div>
                            @error('blackListNumber')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    @if($blackListEntity)
                        <div class="col-md-12 mb-3 form-group">
                            @if($blackListType === \App\Enum\Mvr\MvrBlacklistType::MVR)
                                @include('mvr.reg_info', ['reg' => $blackListEntity])
                            @elseif($blackListType === \App\Enum\Mvr\MvrBlacklistType::DL)
                                @include('driver-license.includes.license_info', ['license' => $blackListEntity])
                            @else
                                <span>Invalid Blacklist Type</span>
                            @endif
                        </div>
                    @endif

                </div>


                <div class="row mx-4 mt-2">
                    <div class="col-md-12 mb-3 form-group">
                        <label>Reason *</label>
                        <textarea class="form-control @error("reason") is-invalid @enderror"
                                  wire:model.defer='reason' rows="4"></textarea>
                        @error("reason")
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3 form-group">
                        <label>Evidence File (Optional)</label>
                        <input type="file" wire:model.defer="evidenceFile" class="form-control">
                        @error("evidenceFile")
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

            </div>


        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger px-2" data-dismiss="modal">Close</button>
            <button class="btn btn-primary rounded-0" wire:click="submit" wire:loading.attr="disable">
                <i class="bi bi-arrow-return-right mr-2" wire:loading.remove wire:target="submit"></i>
                <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                   wire:target="submit"></i>
                {{ __('Submit') }}
            </button>
        </div>
    </div>
</div>
