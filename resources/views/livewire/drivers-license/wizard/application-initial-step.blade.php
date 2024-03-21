<div>
    <div class="row m-2">

        @include('livewire.drivers-license.wizard.application-navigation')

        <br>
        <div class="col-md-6 form-group">
            <br>
            <div>
                <label for="zin">Application Type</label>
                <select wire:model.lazy="type" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}">
                    <option selected>Choose option</option>
                    <option value="fresh">Fresh Applicant</option>
                    <option value="duplicate">Duplicate</option>
                    <option value="renew">Renew Expired License</option>
                </select>
                @error('type')
                <div class="invalid-feedback">
                    {{ $errors->first('type') }}
                </div>
                @enderror
            </div>


            <br>
            <div>
                <label for="zin">Search Applicant By</label>
                <select wire:model.lazy="search_type" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}">
                    <option selected>Choose option</option>
                    @if($type == 'fresh')
                        <option value="tin">TIN Number</option>
                        <option value="zin">Reference/KYC Number</option>
                    @else
                        <option value="license">License Number</option>
                    @endif
                </select>
                @error('search_type')
                <div class="invalid-feedback">
                    {{ $errors->first('search_type') }}
                </div>
                @enderror
            </div>
            <br>
            <div>
                <label for="zin">{{str_replace('ZIN','Ref/KYC',strtoupper($search_type)) ?? ''}} Number</label>
                <input type="text" wire:model.lazy="number"
                       class="form-control {{ $errors->has('number') ? 'is-invalid' : '' }}">
                @error('number')
                <div class="invalid-feedback">
                    {{ $errors->first('number') }}
                </div>
                @enderror
            </div>

            <div class="mt-3">
                <button wire:click="applicantLookup" wire:loading.attr="disabled" class="btn btn-primary">
                    <div wire:loading wire:target="applicantLookup">
                        <div class="spinner-border mr-1 spinner-border-sm text-light">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    search
                </button>
            </div>

        </div>


        <div class="col-6 p-3 border border-white">
            <div class="row">
                <div class="col-12">
                    <br>
                    <h5 class="text-center pb-2 border border-white">Applicant Details</h5>
                    <br>
                </div>
                <hr>
                @if($lookup_fired && !empty($taxpayer))
                    @php($taxpayer = \App\Models\Taxpayer::query()->find($taxpayer->id ?? $taxpayer['id']))
                    <div class="col-md-6 mb-3">
                        <span class="font-weight-bold text-uppercase">Full Name</span>
                        <p class="my-1">{{ "{$taxpayer->first_name} {$taxpayer->middle_name} {$taxpayer->last_name}" }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <span class="font-weight-bold text-uppercase">TIN</span>
                        <p class="my-1">{{ "{$taxpayer->tin}" }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <span class="font-weight-bold text-uppercase">Email Address</span>
                        <p class="my-1">{{ $taxpayer->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <span class="font-weight-bold text-uppercase">Mobile/Alternative</span>
                        <p class="my-1">{{ $taxpayer->mobile }}/{{ $taxpayer->alt_mobile }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <span class="font-weight-bold text-uppercase">Nationality</span>
                        <p class="my-1">{{ $taxpayer->country->nationality }}</p>
                    </div>
                    @if ($taxpayer->zanid_no)
                        <div class="col-md-6 mb-3">
                            <span class="font-weight-bold text-uppercase">ZANID No.</span>
                            <p class="my-1">{{ $taxpayer->zanid_no }}</p>
                        </div>
                    @endif
                    @if ($taxpayer->nida_no)
                        <div class="col-md-6 mb-3">
                            <span class="font-weight-bold text-uppercase">NIDA No.</span>
                            <p class="my-1">{{ $taxpayer->nida_no }}</p>
                        </div>
                    @endif
                    @if ($taxpayer->passport_no)
                        <div class="col-md-6 mb-3">
                            <span class="font-weight-bold text-uppercase">Passport No.</span>
                            <p class="my-1">{{ $taxpayer->passport_no }}</p>
                        </div>
                    @endif

                @elseif($lookup_fired)
                    <p class="my-1 text-danger text-center m-3"> The supplied {{$search_type}} Number does not exist </p>
                @endif
            </div>
        </div>

    </div>

    <div class="row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <a type="button" class="btn btn-danger text-white mr-2" wire:loading.class="disabled"
               href="">
                <i class="bi bi-x-circle-fill mr-1"></i>
                Cancel
            </a>
            <button class="btn btn-primary ml-1" wire:click="nextStep" wire:loading.attr="disabled">
                Next
                <i class="bi bi-chevron-right ml-1"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="nextStep"></i>
            </button>
        </div>
    </div>
</div>