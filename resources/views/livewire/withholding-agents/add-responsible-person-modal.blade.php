<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="text-uppercase">Add Responsible Person</h5>
        </div>
        <div class="modal-body">

            <div class="row mx-4 mt-2">
                <div class="col-md-12 form-group">
                    <label>Responsible Person Name</label>
                    <select wire:model="responsible_person_id"
                        class="form-control {{ $errors->has('responsible_person_id') ? 'is-invalid' : '' }}">
                        <option></option>
                        @foreach ($responsible_persons as $responsible_person)
                            <option value="{{ $responsible_person->id }}">
                                {{ $responsible_person->first_name . ' ' . $responsible_person->middle_name . ' ' . $responsible_person->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('responsible_person_id')
                        <div class="invalid-feedback">
                            {{ $errors->first('responsible_person_id') }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-12 form-group">
                    <label for="title">Title</label>
                    <select wire:model="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}">
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
                <div class="col-md-12 form-group">
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

            

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Save changes</button>
            </div>
        </div>
    </div>
</div>
