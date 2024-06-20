<div class="card">
    <div class="card-body">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            {{ __('Lease Edit Documents') }}
        </div>
        <div class="row mt-3">
            <div class="col-8 text-danger"> {{ __("*Upload new documents (if the documents have changed)") }}</div>
        </div>
        <div class="row mt-3">
            @foreach($leaseDocuments as $file)
                <div class="col-4">
                    <a class="file-item" target="_blank"
                       href="{{ route('land-lease.get.lease.document', ['path' => encrypt($file->file_path)]) }}">
                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                        <div style="font-weight: 500;" class="ml-1">
                            {{ $file->name }}
                        </div>
                    </a>
                </div>
            @endforeach
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

    <div class="row m-3">
        <div class="col-md-12 d-flex justify-content-end">
            <button class="btn btn-warning ml-1" wire:click="submit" wire:loading.attr="disabled">
                {{ __('Submit Changes') }}
                <i class="bi bi-chevron-right ml-1" wire:loading.remove wire:target="submit"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                   wire:target="submit"></i>
            </button>
        </div>
    </div>
</div>
