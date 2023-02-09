<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="text-uppercase">Add Responsible Person</h5>
        </div>
        <div class="modal-body">
            <div class="border-0">
                <div class="row mx-4 mt-2">

                    <div class="col-md-12 form-group">
                        <label for="reference_no">Responsible person ZRA Reference No.</label>
                        <input type="text" wire:model.lazy="reference_no" name="reference_no" id="reference_no"
                            class="form-control {{ $errors->has('reference_no') ? 'is-invalid' : '' }}">
                        @error('reference_no')
                            <div class="invalid-feedback">
                                {{ $errors->first('reference_no') }}
                            </div>
                        @enderror

                        <div class="mt-2">
                            <button wire:click="searchResponsiblePerson" wire:loading.attr="disabled"
                                class="btn btn-primary">
                                <div wire:loading wire:target="searchResponsiblePerson">
                                    <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>Search
                            </button>
                        </div>


                        {{-- Responsible person lookup --}}
                        @if ($search_triggered && !empty($taxpayer))
                            <div class="col-12 p-3" style="border: 1px solid #ede6e6;">
                                <div class="row">
                                    <div class="col-12">
                                        <br>
                                        <h6 class="text-center pb-2" style="border-bottom: 1px solid silver">Responsible
                                            Person
                                            Details
                                        </h6>
                                        <br>
                                    </div>
                                    <hr>

                                    @php($taxpayer = \App\Models\Taxpayer::query()->find($taxpayer->id ?? $taxpayer['id']))
                                    <div class="col-md-6 mb-3">
                                        <span class="font-weight-bold text-uppercase">Full Name</span>
                                        <p class="my-1">
                                            {{ "{$taxpayer->first_name} {$taxpayer->middle_name} {$taxpayer->last_name}" }}
                                        </p>
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
                            @elseif($search_triggered)
                                <p class="my-1 text-danger text-center m-3"> The Responsible person Number does not
                                    exist </p>
                        @endif



                    </div>

                    @if ($search_triggered && !empty($taxpayer))
                        <div class="mt-4">
                            <div class="col-md-12">
                                <label for="title">Title</label>
                                <select wire:model.lazy="title"
                                    class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}">
                                    <option></option>
                                    <option value="Mr">Mr</option>
                                    <option value="Mrs">Mrs</option>
                                    <option value="Sir">Sir</option>
                                    <option value="Madam">Madam</option>
                                    <option value="Dr">Dr</option>
                                    <option value="Prof">Prof</option>
                                    <option value="Hon">Hon</option>
                                    <option value="Other">Other</option>
                                </select>
                                @error('title')
                                    <div class="invalid-feedback">
                                        {{ $errors->first('title') }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="position">Position</label>
                                <input type="text" wire:model.lazy="position" name="position" id="position"
                                    class="form-control {{ $errors->has('position') ? 'is-invalid' : '' }}">
                                @error('position')
                                    <div class="invalid-feedback">
                                        {{ $errors->first('position') }}
                                    </div>
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
                        </div>Save changes
                    </button>
                </div>
            </div>
        </div>
    </div>
