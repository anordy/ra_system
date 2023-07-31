<div class="card shadow-none">
    @include('layouts.component.messages')
    <div class="card-body">
        <h6>Taxpayer & Vessel Information</h6>
        <div class="row">
            <div class="form-group col-lg-6">
                <label class="control-label">Name of Importer/Market (ZTN Location No.) *</label>
                <input type="text" class="form-control @error('location') is-invalid @enderror"
                    wire:model.defer="location">
                @error('location')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Ascertained Date *</label>
                <input type="date" class="form-control @error('ascertained') is-invalid @enderror"
                    wire:model.defer="ascertained">
                @error('ascertained')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Name of Ship *</label>
                <input type="text" class="form-control @error('ship') is-invalid @enderror"
                    wire:model.defer="ship">
                @error('ship')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Port of Disembarkation *</label>
                <input type="text" class="form-control @error('port') is-invalid @enderror"
                    wire:model.defer="port">
                @error('port')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Voyage No: </label>
                <input type="text" class="form-control @error('voyage_no') is-invalid @enderror"
                    wire:model.defer="voyage_no">
                @error('voyage_no')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Quantity of Certificate Attachment: *</label>
                    <div style="flex: 1" class="mr-2" x-init="isUploading = false" x-data="{ isUploading: false, progress: 0 }"
                        x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false"
                        x-on:livewire-upload-error="isUploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress">
                        <input type="file" required accept="application/pdf" class="form-control @error('quantity_certificate_attachment') is-invalid @enderror"
                            wire:model="quantity_certificate_attachment">
                        @error('quantity_certificate_attachment')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                        <div x-show="isUploading">
                            <progress max="100" x-bind:value="progress"></progress>
                        </div>
                </div>
            </div>
            
        </div>
    </div>
    <div class="card-body">
        <h6>Product Information</h6>
        @foreach ($products as $key => $value)
            <div class="mt-2 border p-2">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label class="control-label">Intended Cargo Discharge</label>
                        <select class="form-control" wire:model.defer="products.{{ $key }}.config_id">
                            <option value="" selected> --Select--</option>
                            @foreach ($configs as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error("products.{$key}.config_id")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="control-label">Liters Observed</label>
                        <input type="number" class="form-control"
                            wire:model.defer="products.{{ $key }}.liters_observed">
                        @error("products.{$key}.liters_observed")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="control-label">Liters At 20 <sup>o</sup> C</label>
                        <input type="number" class="form-control"
                            wire:model.defer="products.{{ $key }}.liters_at_20">
                        @error("products.{$key}.liters_at_20")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Metric Tons in Air</label>
                        <input type="number" class="form-control"
                            wire:model.defer="products.{{ $key }}.metric_tons">
                        @error("products.{$key}.metric_tons")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        @if ($key > 0)
                            <div>
                                <button class="btn btn-danger btn-sm"
                                    wire:click.prevent="removeProduct({{ $key }})">
                                    Remove Product
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
        <div class="row mt-3">
            <div class="col-md-4">
                <button class="btn text-white btn-info btn-sm" wire:click.prevent="addProduct()">Add Product</button>
            </div>
        </div>


    </div>
    <div class="card-footer bg-white d-flex justify-content-end">
        <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
            <div wire:loading.delay wire:target="submit">
                <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>Save & Generate Certificate</button>
    </div>
</div>
