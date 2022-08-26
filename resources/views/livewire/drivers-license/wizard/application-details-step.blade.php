<div>
    <div class="row m-2">

        @include('livewire.drivers-license.wizard.application-navigation')


        <br>
        <div class="col-md-6 form-group">
            <br>
            <div  class="p-1">
                <div>
                    <label for="dob">Date of Birth</label>
                    <input wire:model.lazy="dob" {{$editable ? '':'disabled'}}  {{$editable ? 'type="date"':('value="'.$dob.'"')}}
                           class="form-control {{ $errors->has('dob') ? 'is-invalid' : '' }}">
                    @error('dob')
                    <div class="invalid-feedback">
                        {{ $errors->first('dob') }}
                    </div>
                    @enderror
                </div>
            </div>
            <div  class="p-1">
                <label for="zin">Blood Group</label>
                <select wire:model.lazy="blood_group_id" {{$editable ? '':'disabled'}} class="form-control {{ $errors->has('blood_group_id') ? 'is-invalid' : '' }}">
                    <option value>Choose Group</option>
                    @foreach(\App\Models\DlBloodGroup::all() as $group)
                        <option value="{{$group->id}}" {{$group->id==$blood_group_id?'selected':''}}>{{$group->name}}</option>
                    @endforeach
                </select>
                @error('blood_group_id')
                <div class="invalid-feedback">
                    {{ $errors->first('blood_group_id') }}
                </div>
                @enderror
            </div>

            <div  class="p-1">
                <label for="cert_number">Certificate #</label>
                <input type="text" wire:model.lazy="cert_number" {{$editable ? '':'disabled'}}
                       class="form-control {{ $errors->has('cert_number') ? 'is-invalid' : '' }}">
                @error('cert_number')
                <div class="invalid-feedback">
                    {{ $errors->first('cert_number') }}
                </div>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <br>
            <div class="p-1">
                <label for="conf_number">Confirmation #</label>
                <input type="text" wire:model.lazy="conf_number" {{$editable ? '':'disabled'}}
                       class="form-control {{ $errors->has('conf_number') ? 'is-invalid' : '' }}">
                @error('conf_number')
                <div class="invalid-feedback">
                    {{ $errors->first('conf_number') }}
                </div>
                @enderror
            </div>


            <div class="p-1">
                <label for="conf_number">Competence #</label>
                <input type="text" wire:model.lazy="comp_number" {{$editable ? '':'disabled'}}
                       class="form-control {{ $errors->has('comp_number') ? 'is-invalid' : '' }}">
                @error('comp_number')
                <div class="invalid-feedback">
                    {{ $errors->first('comp_number') }}
                </div>
                @enderror
            </div>
        </div>

    </div>

        <div class="row mt-3">
            <div class="col-md-12 d-flex justify-content-end">
                <a type="button" class="btn btn-danger text-white mr-2" wire:loading.class="disabled"
                   href="">
                    <i class="bi bi-x-circle mr-1"></i>
                    Cancel
                </a>
                <button type="button" class="btn btn-primary mr-1" wire:click="previousStep"
                        wire:loading.attr="disabled">
                    <i class="bi bi-chevron-left mr-1" wire:loading.remove wire:target="previousStep"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                       wire:target="previousStep"></i>
                    Previous
                </button>
                <button class="btn btn-primary ml-1" wire:click="nextStep" wire:loading.attr="disabled">
                    Next
                    <i class="bi bi-chevron-right ml-1" wire:loading.remove wire:target="nextStep"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                       wire:target="nextStep"></i>
                </button>
            </div>
        </div>
    </div>

</div>