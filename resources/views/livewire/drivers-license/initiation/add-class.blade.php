<div>
    <div class="card rounded-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 pt-3">
                    <h4>Initiating Adding Class To License</h4>
                    <p class="mb-3">Provide the required applicant information to continue</p>
                    <hr/>
                </div>

                <div class="col-md-4">
                    <div class="input-group mb-3">
                        <input type="text" wire:model.defer="licenseNumber"
                               class="form-control @error('licenseNumber') is-invalid @enderror"
                               placeholder="Enter License Number">
                        <div class="input-group-append">
                            <button class="btn btn-info" type="button" wire:click="searchLicense()">
                                <i class="bi bi-search mr-1" wire:loading.remove wire:target="searchLicense"></i>
                                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                                   wire:target="searchLicense"></i>
                                Search
                            </button>
                        </div>
                    </div>
                    <small class="form-text text-muted">{{ __('Enter the active license number') }}
                        .</small>
                    @error('licenseNumber')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            @if($licenseInfo)
                <div class="mt-4">
                    <h6>License Class</h6>
                    <hr/>
                    @foreach($licenseClasses ?? [] as $i => $class)
                        <div class="row mb-3">
                            <x-select col="3" disabled="{{ $class['disabled'] }}" name="licenseClasses.{{$i}}.classId" :options="$classes" label="Class"
                                      required></x-select>
                            <x-input col="3" type="text" disabled="{{ $class['disabled'] }}"  name="licenseClasses.{{$i}}.certificateNumber"
                                     label="Certificate Number" required></x-input>
                            <x-input col="3" type="date" disabled="{{ $class['disabled'] }}"  name="licenseClasses.{{$i}}.certificateDate"
                                     label="Certificate Date"
                                     required></x-input>

                            @if ($i > 0 && !$class['disabled'])
                                <div class="col-md-1 mt-4">
                                    <button wire:click="removeClass({{$i}})" class="btn btn-danger"><i
                                                class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <button class="btn btn-success d-flex column-gap-2" wire:click="addClass()">
                        <i class="bi bi-plus-circle-fill mr-1"></i> {{ __('Add Class') }}
                    </button>

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button wire:loading.disable class="btn btn-primary px-4 mt-3" type="submit"
                                    wire:click="submit">
                                <i class="bi bi-person-plus mr-2" wire:loading.remove wire:target="submit"></i>
                                <i class="spinner-border spinner-border-sm  mr-2" role="status" wire:loading
                                   wire:target="submit"></i>
                                Save & Initiate License Application
                            </button>
                        </div>

                    </div>
                </div>

            @endif

        </div>
    </div>
</div>