<div>
    <div>
        <div class="card-header text-uppercase font-weight-bold bg-white">
            {{ __('Lease Information') }}
        </div>
        @foreach ($files as $index => $file)
            <div class="row mb-3 mt-3">

                <div class="col-md-4">
                    <label>{{__("Document Name*")}}</label>
                    <input type="text" wire:model.defer="files.{{ $index }}.name" class="form-control"
                           placeholder="Enter Document Name" value="{{ $file["name"] }}">
                    @error("files.$index.name")
                    <span class="text-danger">{{ __("Document name is required") }}</span>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label>{{__("File*")}}</label>
                    <input type="file" wire:model.defer="files.{{ $index }}.file" class="form-control">
                    @error("files.$index.file")
                    <span class="text-danger">{{ __("This field is required. PDF Format accepted") }}</span>
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

        <button wire:click="addFileInput" wire:loading.attr="disabled" class="btn btn-primary">Add More
            File
        </button>
    </div>

    <div class="row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <button class="btn btn-primary ml-1" wire:click="submit" wire:loading.attr="disabled">
                {{ __('Submit') }}
                <i class="bi bi-chevron-right ml-1" wire:loading.remove wire:target="submit"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                   wire:target="submit"></i>
            </button>
        </div>
    </div>
</div>
