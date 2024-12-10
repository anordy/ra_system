<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="text-uppercase">Report an Incident</h5>
        </div>
        <div class="modal-body">
            <div class="border-0">
                <div class="row mx-4 mt-2">
                    <div class="col-md-12 form-group">
                        <label for="reference_no">Title *</label>
                        <input type="text" wire:model.defer="title"
                               class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}">
                        @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-6 form-group">
                        <label>{{ __('Category') }} *</label>
                        <select wire:model="categoryId" class="form-control @error('categoryId') is-invalid @enderror">
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach ($categories ?? [] as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('categoryId')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-6 form-group">
                        <label>{{ __('Sub Category') }} *</label>
                        <select wire:model="subCategoryId"
                                class="form-control @error('subCategoryId') is-invalid @enderror">
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach ($subCategories ?? [] as $subCategory)
                                <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                            @endforeach
                        </select>
                        @error('subCategoryId')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3 form-group">
                        <label>{{ __("Description") }} *</label>
                        <textarea class="form-control @error("description") is-invalid @enderror"
                                  wire:model.defer='description' rows="4"></textarea>
                        @error("description")
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                </div>


                @foreach ($files as $index => $file)
                    <div class="row mx-4">
                        <div class="col-md-6">
                            <input type="text" wire:model.defer="files.{{ $index }}.name" class="form-control"
                                   placeholder="Enter Document Name" value="{{ $file["name"] }}">
                            @error("files.$index.name")
                            <span class="text-danger">{{ __("Please Enter Name of The Document") }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input type="file" wire:model.defer="files.{{ $index }}.file" class="form-control">
                            @error("files.$index.file")
                            <span class="text-danger">{{ __("Please Upload Valid Document File  In PDF or EXCEL Format") }}</span>
                            @enderror
                        </div>
                        @if ($index > 0)
                            <div class="col-md-1">
                                <button wire:click="removeFileInput({{ $index }})" class="btn btn-danger">
                                    Remove
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach

            </div>
        </div>
        <div class="modal-footer">
            <button wire:click="addFileInput" wire:loading.attr="disabled" class="btn btn-primary">Add More
                File
            </button>
            <button type="button" class="btn btn-danger px-2" data-dismiss="modal">Close</button>
            <button class="btn btn-primary rounded-0" wire:click="submit" wire:loading.attr="disable">
                <i class="bi bi-arrow-return-right mr-2" wire:loading.remove wire:target="submit"></i>
                <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                   wire:target="submit"></i>
                {{ __('Submit') }}
            </button>
        </div>
    </div>
</div>