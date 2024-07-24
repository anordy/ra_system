<div class="card">
    <div class="card-header text-uppercase font-weight-bold bg-white">
        {{ __('Upload Lease Information') }}
    </div>
    <div class="card-body mx-2">

        @foreach ($files as $index => $file)
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>{{__("Document Name*")}}</label>
                    <input type="text" wire:model.defer="files.{{ $index }}.name" class="form-control"
                           placeholder="Enter Document Name" value="{{ $file["name"] }}">
                    @error("files.$index.name")
                    <span class="text-danger">{{ __("Document name is required") }}</span>
                    @enderror
                </div>
                <div class="col-md-4"
                     x-data="{ isUploading: false, progress: 0 }"
                     x-on:livewire-upload-start="isUploading = true"
                     x-on:livewire-upload-finish="isUploading = false"
                     x-on:livewire-upload-error="isUploading = false"
                     x-on:livewire-upload-progress="progress = $event.detail.progress"
                >
                    <label>{{__("File*")}}</label>
                    <input type="file" wire:model.defer="files.{{ $index }}.file" class="form-control">
                    <div x-show="isUploading">
                        <progress max="100" x-bind:value="progress"></progress>
                    </div>
                    @error("files.$index.file")
                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
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

        <div class="row">
            <div class="col-md-12 d-flex justify-content-end">
                <button wire:click="addFileInput" wire:loading.attr="disabled" class="btn btn-info">
                    <i class="bi bi-plus-circle-fill mr-1"></i> Add File
                </button>
                <button class="btn btn-primary ml-1" wire:click="submit" wire:loading.attr="disabled" x-bind:disabled="isUploading">
                    {{ __('Submit For Registration') }}
                    <i class="bi bi-chevron-right ml-1" wire:loading.remove wire:target="submit"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                       wire:target="submit"></i>
                </button>
            </div>
        </div>
    </div>
</div>
