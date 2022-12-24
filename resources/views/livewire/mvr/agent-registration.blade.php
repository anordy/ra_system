<div class="row m-2">
    <div class="col-md-12 form-group">
        <label for="zin">Agent Reference/KYC Number</label>
        <input type="text" wire:model.lazy="zin"
               class="form-control {{ $errors->has('zin') ? 'is-invalid' : '' }}">
        @error('zin')
        <div class="invalid-feedback">
            {{ $errors->first('zin') }}
        </div>
        @enderror
    </div>

    <div class="col-md-12">
        <button wire:click="lookup" wire:loading.attr="disabled" class="btn btn-primary">
            <div wire:loading wire:target="submit">
                <div class="spinner-border mr-1 spinner-border-sm text-light">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            Lookup
        </button>
    </div>

    <br>
    <br>
    <br>
    <div class="col-md-12 form-group">
        <br>
        @if($lookup_fired && !empty($taxpayer))
            <div class="card">
                <div class="card-body">
                    <h5>Look up details</h5>
                    <hr/>
                    <div class="row my-2">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Full Name</span>
                            <p class="my-1">{{ "{$taxpayer->first_name} {$taxpayer->middle_name} {$taxpayer->last_name}" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Email Address</span>
                            <p class="my-1">{{ $taxpayer->email }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Mobile</span>
                            <p class="my-1">{{ $taxpayer->mobile }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Alternative Mobile</span>
                            <p class="my-1">{{ $taxpayer->alt_mobile }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Nationality</span>
                            <p class="my-1">{{ $taxpayer->country->nationality }}</p>
                        </div>
                        @if ($taxpayer->zanid_no)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">ZANID No.</span>
                                <p class="my-1">{{ $taxpayer->zanid_no }}</p>
                            </div>
                        @endif
                        @if ($taxpayer->nida_no)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">NIDA No.</span>
                                <p class="my-1">{{ $taxpayer->nida_no }}</p>
                            </div>
                        @endif
                        @if ($taxpayer->passport_no)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Passport No.</span>
                                <p class="my-1">{{ $taxpayer->passport_no }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12 d-flex justify-content-center">
                    <a href="{{ route('mvr.agent') }}" class="btn btn-danger mr-2">Cancel</a>
                    <button wire:click="submit" wire:loading.attr="disabled" class="btn btn-primary">
                        <div wire:loading wire:target="submit">
                            <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>Register as Agent</button>
                </div>
            </div>
        @elseif($lookup_fired)
            <p class="my-1 text-danger"> The supplied Reference/KYC No. does not exist </p>
        @endif
    </div>

</div>
