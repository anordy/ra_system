<div>
    <div class="row m-2">

        @include('livewire.drivers-license.wizard.application-navigation')


        <br>
        <div class="col-md-6 form-group">
            <br>

            <div  class="p-1">
                <label>License Classes</label>
                <br>
                @if($type=='fresh' || $type=='renew')
                     @foreach(\App\Models\DlLicenseClass::all() as $group)
                       <div style="border: 1px solid silver; padding: 8px; border-radius: 3px;">
                           <input type="checkbox" value="{{$group->id}}" id="lc-{{$group->id}}" wire:model.lazy="license_class_ids.{{$group->id}}" >
                           <label for="lc-{{$group->id}}" class="font-weight-bold">{{$group->name.' - '.$group->description}}</label>
                       </div>
                    @endforeach
                @else
                    @foreach($classes as $class)
                        <div style="border: 1px solid silver; padding: 6px; border-radius: 3px;">
                            <label  class="font-weight-bold">{{\App\Models\DlLicenseClass::query()->find($class['dl_license_class_id'])->name}}</label>
                        </div>
                    @endforeach
                @endif
            </div>

            @if($type=='fresh' || $type=='renew')
            <div  class="p-1">
                <label for="duration_id">License Duration</label>
                <select wire:model.lazy="duration_id" class="form-control {{ $errors->has('duration_id') ? 'is-invalid' : '' }}">
                    <option value>Choose type</option>
                    @foreach(\App\Models\DlLicenseDuration::all() as $group)
                        <option value="{{$group->id}}">{{$group->number_of_years.' - '.$group->description}}</option>
                    @endforeach
                </select>
                @error('duration_id')
                <div class="invalid-feedback">
                    {{ $errors->first('duration_id') }}
                </div>
                @enderror
            </div>
            @else
                <div  class="p-1">
                    <label for="duration_id">License Duration</label>
                    <select wire:model.lazy="duration_id" class="form-control {{ $errors->has('duration_id') ? 'is-invalid' : '' }}" disabled>
                        <option value>Choose type</option>
                        @foreach(\App\Models\DlLicenseDuration::query()->where(['id'=>$this->duration_id])->get() as $group)
                            <option value="{{$group->id}}">{{$group->number_of_years.' - '.$group->description}}</option>
                        @endforeach
                    </select>
                    @error('duration_id')
                    <div class="invalid-feedback">
                        {{ $errors->first('duration_id') }}
                    </div>
                    @enderror
                </div>
            @endif

            @if($editable)
                <div  class="p-1">
                    <label for="restrictions">License Restrictions</label>
                    <textarea wire:model.lazy="restrictions" id="restrictions"
                              class="form-control {{ $errors->has('restrictions') ? 'is-invalid' : '' }}"></textarea>
                    @error('restrictions')
                    <div class="invalid-feedback">
                        {{ $errors->first('restrictions') }}
                    </div>
                    @enderror
                </div>
            @endif

        </div>

    </div>

        <div class="row mt-3">
            <div class="col-md-12 d-flex justify-content-end">
                <a type="button" class="btn btn-danger text-white mr-2" wire:loading.class="disabled"
                   href="">
                    <i class="bi bi-x-circle-fill mr-1"></i>
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
                    Complete & Save
                    <i class="bi bi-chevron-right ml-1" wire:loading.remove wire:target="nextStep"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                       wire:target="nextStep"></i>
                </button>
            </div>
        </div>
</div>