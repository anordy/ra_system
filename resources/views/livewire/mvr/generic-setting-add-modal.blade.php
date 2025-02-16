<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">{{$instance?'Update':'Add'}} {{$setting_title}}</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="bi bi-x-circle-fill"></i></button>
            </div>
            @include('components.errors')
            <div class="modal-body">
                @if($this->hasNameColumn())
                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-12">
                            <label class="control-label">Name</label>
                            <input type="text" class="form-control" wire:model.lazy="name" id="name">
                            @error('name')
                            <small class="text-danger pt-2">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                @endif

                @foreach($relation_options as $field=>$relation)
                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-12">
                            <label class="control-label">{{$relation['title']}}</label>
                            <select wire:model="relation_data.{{$field}}" class="form-control" id="{{$field}}">
                                <option>Choose option</option>
                                @php
                                    if(is_string($relation['data'])) eval($relation['data'])
                                @endphp
                                @foreach ($relation['data'] as $option)
                                    <option value="{{ $option['id'] }}">{{ $option['name']?? $option['type'] ?? $option[$relation['value_field']] }}</option>
                                @endforeach
                            </select>
                            @error("relation_data.{$field}")
                            <small class="text-danger pt-2">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                @endforeach

                @foreach($field_options as $f=>$options)
                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-12">
                            <label class="control-label" for="{{$f}}">{{$options['title']}}</label>
                            <input type="{{$this->getFieldInputType($f)}}" class="form-control"
                                   wire:model.lazy="data.{{$f}}" id="{{$f}}"
                                   placeholder="{{ $options['placeholder'] ?? '' }}">
                            @error("data.{$f}")
                            <small class="text-danger pt-2">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                @endforeach


                @foreach($enum_options as $field=>$enum)
                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-12">
                            <label class="control-label">{{$enum['title']}}</label>
                            <select wire:model="data.{{$field}}" class="form-control" id="{{$field}}">
                                <option>Choose option</option>
                                @foreach ($enum['data'] as $key=>$title)
                                    <option value="{{ $key }}">{{ $title }}</option>
                                @endforeach
                            </select>
                            @error("data.{$field}")
                            <small class="text-danger pt-2">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                @endforeach

                @if(isset($check_options[$this->model]))
                    @foreach($check_options[$this->model] as $key => $option)
                        <div>
                            <input type="checkbox" value="{{ $option['id'] }}"
                                   wire:model="data.checkboxes.{{ $option['id'] }}">
                            <label>
                                {{ $option['name'] }}
                            </label>
                            @error('data.checkboxes' . $option['id']) <span>{{ $message }}</span> @enderror
                        </div>
                    @endforeach
                @endif

                @if($model == \App\Models\MvrFee::class && $relation_data['mvr_registration_type_id'])
                    @if(!in_array(\App\Models\MvrRegistrationType::find($relation_data['mvr_registration_type_id'])->name, [
                        \App\Models\MvrRegistrationType::TYPE_CORPORATE,
                        \App\Models\MvrRegistrationType::TYPE_GOVERNMENT_INTERNATIONAL,
                        \App\Models\MvrRegistrationType::TYPE_GOVERNMENT_DIPLOMATIC,
                        \App\Models\MvrRegistrationType::TYPE_GOVERNMENT_MILITARY,
                        \App\Models\MvrRegistrationType::TYPE_GOVERNMENT_DONOR_FUNDED,
                        \App\Models\MvrRegistrationType::TYPE_GOVERNMENT_SLS,
                        \App\Models\MvrRegistrationType::TYPE_GOVERNMENT_SMZ,
                    ]))
                        <div class="row pr-3 pl-3">
                            <div class="form-group col-lg-12">
                                <label class="control-label">Registration No. Type</label>
                                <select wire:model="plateNoType" class="form-control">
                                    <option>Choose option</option>
                                    @foreach (\App\Models\MvrPlateNumberType::all() as $key => $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error("plateNoType")
                                <small class="text-danger pt-2">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    @endif

                @else
                    @php($plateNoType = null)
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button wire:click="submit" wire:loading.attr="disabled" class="btn btn-primary">
                    <div wire:loading wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
