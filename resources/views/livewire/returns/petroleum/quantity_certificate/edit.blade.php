<div class="card shadow-none">
    @include('layouts.component.messages')
    <div class="card-body">
        <h6>Taxpayer & Vessel Information</h6>
        <div class="row">
            <div class="form-group col-lg-6">
                <label class="control-label">Name of Importer/Market (ZRB No.)</label>
                <input type="text" class="form-control @error('business') is-invalid @enderror"
                    wire:model.lazy="business">
                @error('business')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Ascertained Date</label>
                <input type="date" class="form-control @error('ascertained') is-invalid @enderror"
                    wire:model.lazy="ascertained">
                @error('ascertained')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Name of Ship</label>
                <input type="text" class="form-control @error('ship') is-invalid @enderror"
                    wire:model.lazy="ship">
                @error('ship')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Port of Disembarkation</label>
                <input type="text" class="form-control @error('port') is-invalid @enderror"
                    wire:model.lazy="port">
                @error('port')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Voyage No:</label>
                <input type="text" class="form-control @error('voyage_no') is-invalid @enderror"
                    wire:model.lazy="voyage_no">
                @error('voyage_no')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
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
                        <select class="form-control" wire:model="products.{{ $key }}.config_id">
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
                            wire:model="products.{{ $key }}.liters_observed">
                        @error("products.{$key}.liters_observed")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="control-label">Liters At 20 <sup>o</sup> C</label>
                        <input type="number" class="form-control"
                            wire:model="products.{{ $key }}.liters_at_20">
                        @error("products.{$key}.liters_at_20")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Metric Tons in Air</label>
                        <input type="number" class="form-control"
                            wire:model="products.{{ $key }}.metric_tons">
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
        <button type="button" class="btn btn-primary" wire:click='submit'>Save & Generate Certificate</button>
    </div>
</div>
