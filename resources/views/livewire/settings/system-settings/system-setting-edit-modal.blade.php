<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Edit System Setting Category {{$systemSetting->name}}</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Category</label>
                        <select type="text" class="form-control" wire:model.lazy="system_setting_category" id="system_setting_category">
                            @foreach ($categories as $category)
                                <option {{($category->id == $systemSetting->system_setting_category_id) ? 'selected' : ''}} value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('system_setting_category')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Name</label>
                        <input type="text" class="form-control" wire:model.defer="name" id="name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    @if($settingCategory == \App\Models\SystemSettingCategory::CERTIFICATE_SETTINGS)
                        <div class="form-group col-lg-12">
                            <label class="control-label">Value Type</label>
                            <div class="d-flex">
                                <div class="form-check mx-2">
                                    <input class="form-check-input" type="radio" wire:model.lazy="valueType" value="text" checked>
                                    <label class="form-check-label" for="exampleRadios1">
                                        Number
                                    </label>
                                </div>
                                <div class="form-check mx-2">
                                    <input class="form-check-input" type="radio" wire:model.lazy="valueType" value="file">
                                    <label class="form-check-label" for="exampleRadios1">
                                        File
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($settingCategory == \App\Models\SystemSettingCategory::FILING_DEADLINE)
                        <div class="form-group col-lg-12">
                            <label class="control-label">Value</label>
                            <input type="time" class="form-control" wire:model.lazy="value" id="value">
                            @error('value')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @elseif($unit === \App\Models\SystemSetting::INPUT_OPTIONS)
                        <div class="form-group col-lg-12">
                            <label class="control-label">Value</label>
                            <select class="form-control" wire:model.lazy="value">
                                @foreach($this->options as $key => $option)
                                <option value="{{ $option }}">{{$key}}</option>
                                @endforeach
                            </select>
                            @error('value')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @else
                        <div class="form-group col-lg-12">
                            <label class="control-label">Value</label>
                            <input type="text" class="form-control" wire:model.lazy="value" id="value">
                            @error('value')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                    <div class="form-group col-lg-12">
                        <label class="control-label">Code</label>
                        <input type="text" disabled class="form-control" wire:model.defer="code" id="code">
                        @error('code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Unit</label>
                        <input type="text" class="form-control" wire:model.defer="unit" id="unit">
                        @error('unit')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label">Description</label>
                        <textarea type="text" rows="3" class="form-control" wire:model.defer="description" id="description"></textarea>
                        @error('description')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Update changes
                </button>
            </div>
        </div>
    </div>
</div>
