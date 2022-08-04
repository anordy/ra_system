<div>
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Registrations
        </div>
        <div class="card-body">
            <div class="row mx-4 mt-2">
                <div class="col-md-6 form-group">
                    <label for="institution_name">Business Name</label>
                    <input type="text" wire:model.lazy="institution_name"
                        class="form-control {{ $errors->has('institution_name') ? 'is-invalid' : '' }}">
                    @error('institution_name')
                        <div class="invalid-feedback">
                            {{ $errors->first('institution_name') }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label for="address">Business Location</label>
                    <input type="text" wire:model.lazy="address"
                        class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}">
                    @error('address')
                        <div class="invalid-feedback">
                            {{ $errors->first('address') }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="institution_place">Main Project</label>
                    <input type="text" wire:model.lazy="institution_place" name="institution_place"
                        id="institution_place"
                        class="form-control {{ $errors->has('institution_place') ? 'is-invalid' : '' }}">
                    @error('institution_place')
                        <div class="invalid-feedback">
                            {{ $errors->first('institution_place') }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="mobile">Project</label>
                    <input type="text" maxlength="10" wire:model.lazy="mobile" name="mobile" id="mobile"
                        class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}">
                    @error('mobile')
                        <div class="invalid-feedback">
                            {{ $errors->first('mobile') }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="email">Rate</label>
                    <input type="email" wire:model.lazy="email" name="email" id="email"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @enderror
                </div>


            </div>
        </div>
    </div>
    <div class="card p-0 m-0 mt-2">
        <div class="card-header text-uppercase font-weight-bold">
            Items
        </div>
        <div class="card-body">
            <table class="table table-border">
                <tr>
                    <td>
                        <div class="form-group">
                            <label for="institution_name">Name</label>
                            <input type="text" wire:model.lazy="institution_name"
                                class="form-control {{ $errors->has('institution_name') ? 'is-invalid' : '' }}">
                            @error('institution_name')
                                <div class="invalid-feedback">
                                    {{ $errors->first('institution_name') }}
                                </div>
                            @enderror
                        </div>
                    </td>
                    <td>

                        <div class="form-group">
                            <label for="address">Business Description</label>
                            <input type="text" wire:model.lazy="address"
                                class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}">
                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $errors->first('address') }}
                                </div>
                            @enderror
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="institution_place">Quantity</label>
                            <input type="text" wire:model.lazy="institution_place" name="institution_place"
                                id="institution_place"
                                class="form-control {{ $errors->has('institution_place') ? 'is-invalid' : '' }}">
                            @error('institution_place')
                                <div class="invalid-feedback">
                                    {{ $errors->first('institution_place') }}
                                </div>
                            @enderror
                        </div>
                    </td>
                    <td>

                    </td>
                </tr>
            </table>
            
        </div>
    </div>

    <div class="card p-0 m-0 mt-2">
        <div class="card-header text-uppercase font-weight-bold">
            Attachments
        </div>
        <div class="card-body">
            <table class="table table-border">
                <tr>
                    <td>
                        <div class="form-group">
                            <label for="institution_name">Name</label>
                            <input type="text" wire:model.lazy="institution_name"
                                class="form-control {{ $errors->has('institution_name') ? 'is-invalid' : '' }}">
                            @error('institution_name')
                                <div class="invalid-feedback">
                                    {{ $errors->first('institution_name') }}
                                </div>
                            @enderror
                        </div>
                    </td>
                    <td>

                        <div class="form-group">
                            <label for="address">File</label>
                            <input type="text" wire:model.lazy="address"
                                class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}">
                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $errors->first('address') }}
                                </div>
                            @enderror
                        </div>
                    </td>
                    <td>
                       
                    </td>
                </tr>
            </table>
            
        </div>
    </div>

</div>
