<div>
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Supplier
        </div>
        <div class="card-body p-1">
            <div class="row mx-4 mt-2">
                <div class="col-4 form-group">
                    <label for="supplier_name" class="d-flex justify-content-between'">
                        <span>
                            Supplier *
                        </span>
                    </label>
                    <select name="supplier" id="supplier_name" wire:model="supplier"
                        class="form-control {{ $errors->has($supplier) ? 'is-invalid' : '' }}">
                        <option value="">Select Supplier</option>
                        @foreach ($optionSuppliers as $optionSupplier)
                            <option value="{{ $optionSupplier->id }}">
                                {{ $optionSupplier->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                @if ($optionSupplierLocations)
                    <div class="col-4 form-group">
                        <label for="supplier_location" class="d-flex justify-content-between'">
                            <span>
                                Supplier Location *
                            </span>
                        </label>
                        <select name="supplierLocation" id="supplier_location" wire:model="supplierLocation"
                            class="form-control {{ $errors->has($supplierLocation) ? 'is-invalid' : '' }}">
                            @foreach ($optionSupplierLocations as $optionSupplierLocation)
                                <option value="{{ $optionSupplierLocation->id }}">
                                    {{ $optionSupplierLocation->name ?? $optionSupplierLocation->business->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplierLocation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                @endif

            </div>

        </div>
    </div>
    <div class="card p-0 m-0 mt-3">
        <div class="card-header text-uppercase font-weight-bold">
            Project
        </div>
        <div class="card-body p-1">
            <div class="row mx-4 mt-2">
                <div class="col-4 form-group">
                    <label for="projectSection_name" class="d-flex justify-content-between'">
                        <span>
                            Project Section*
                        </span>
                    </label>
                    <select name="projectSection" id="projectSection_name" wire:model="projectSection"
                        class="form-control {{ $errors->has($projectSection) ? 'is-invalid' : '' }}">
                        <option value="">Select Project Section</option>
                        @foreach ($optionProjectSections as $optionProjectSection)
                            <option value="{{ $optionProjectSection->id }}">
                                {{ $optionProjectSection->name }}</option>
                        @endforeach
                    </select>
                    @error('projectSection')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                @if ($optionProjects)
                    <div class="col-4 form-group">
                        <label for="Projects" class="d-flex justify-content-between'">
                            <span>
                                Project *
                            </span>
                        </label>
                        <select name="project" id="Projects" wire:model="project"
                            class="form-control {{ $errors->has($project) ? 'is-invalid' : '' }}">

                            @foreach ($optionProjects as $optionProject)
                                <option value="{{ $optionProject->id }}">
                                    {{ $optionProject->name }}</option>
                            @endforeach
                        </select>
                        @error('project')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                @endif
                @if ($rate)
                    <div class="col-4 form-group pt-4 text-center">
                        <h6>Rate : {{ number_format($rate, 1) }}%</h6>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="card p-0 m-0 mt-3">
        <div class="card-header text-uppercase font-weight-bold">
            Items
        </div>
        <div class="card-body">
           
            <table class="table table-border table-bordered">
                <thead>
                    <tr>
                        <th scope="col" style="text-align:center">SN</th>
                        <th scope="col" style="text-align:center">Item Name *</th>
                        <th scope="col" style="text-align:center">Quantity *</th>
                        <th scope="col" style="text-align:center">Unit Cost*</th>
                        <th scope="col" style="text-align:center">Unit Name</th>
                        <th scope="col" style="text-align:center">Amount</th>
                    </tr>
                </thead>
                @foreach ($items as $i => $item)
                    <tr>
                        <td>
                            {{ $i + 1 }}
                        </td>
                        <td>
                            <div class="input-group @error('item.' . $i) is-invalid @enderror">
                                <input class="form-control @error('items.' . $i . '.name') is-invalid @enderror"
                                    wire:model.lazy="items.{{ $i }}.name" />
                                @error('items.' . $i . '.name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </td>
                        <td>
                            <div class="input-group @error('item.' . $i) is-invalid @enderror">
                                <input class="form-control @error('items.' . $i . '.quantity') is-invalid @enderror"
                                    wire:model.lazy="items.{{ $i }}.quantity" type="number" min="0"
                                    wire:change="calculateAmountPayable({{ $i }})" />
                                @error('items.' . $i . '.quantity')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </td>

                        <td>
                            <div class="input-group @error('item.' . $i) is-invalid @enderror">
                                <input class="form-control @error('items.' . $i . '.costPerItem') is-invalid @enderror"
                                    wire:model.lazy="items.{{ $i }}.costPerItem" type="number"
                                    min="0" wire:change="calculateAmountPayable({{ $i }})" />
                                @error('items.' . $i . '.costPerItem')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </td>
                        <td>
                            <div class="input-group @error('item.' . $i) is-invalid @enderror">
                                <input class="form-control @error('items.' . $i . '.unit') is-invalid @enderror"
                                    wire:model.lazy="items.{{ $i }}.unit" />
                                @error('items.' . $i . '.unit')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </td>
                        <td>
                            @if ($item['amount'] != null)
                                {{ number_format($item['amount']) }}
                            @endif
                        </td>
                        <td style="min-width: 100%">
                            @if (count($items) > 1)
                                <div class="text-right mt-2">
                                    <button class="btn btn-danger btn-sm" wire:click="removeItem({{ $i }})">
                                        <i class="bi bi-x-lg mr-1"></i>
                                        <small> Remove </small>
                                    </button>
                                </div>
                            @endif
                        </td>

                    </tr>
                @endforeach

            </table>
            <div class="text-right mt-2">
                <button class="btn btn-secondary" wire:click="addItem()">
                    <i class="bi bi-plus-circle mr-1"></i>
                    Add Item
                </button>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                @if ($total !== null)
                    <div class="col-md-7"></div>
                    <div class="col-md-5 text-right">
                        <div class="row border ">
                            <div class="col-6">
                                <h6> Total : </h6>
                            </div>
                            <div class="col-6 border ">
                                <h6>{{ number_format($total, 2) }}</h6>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                @if ($vatAmount !== null)
                    <div class="col-md-7"></div>
                    <div class="col-md-5 text-right">
                        <div class="row border ">
                            <div class="col-6">
                                <h6> VAT (15%) : </h6>
                            </div>
                            <div class="col-6 border ">
                                <h6> {{ number_format($vatAmount, 2) }}</h6>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                @if ($relievedAmount !== null)
                    <div class="col-md-7"></div>
                    <div class="col-md-5 text-right">
                        <div class="row border">
                            <div class="col-6">
                                <h6> Relieved Amount ({{ number_format($rate, 1) }}%) : </h6>
                            </div>
                            <div class="col-6 border">
                                <h6> {{ number_format($relievedAmount, 2) }}</h6>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                @if ($amountPayable !== null)
                    <div class="col-md-7"></div>
                    <div class="col-md-5 text-right">
                        <div class="row border">
                            <div class="col-6">
                                <h6> Amount Payable : </h6>
                            </div>
                            <div class="col-6 border">
                                <h6>{{ number_format($amountPayable, 2) }}</h6>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="card p-0 m-0 mt-3">
        <div class="card-header text-uppercase font-weight-bold">
            Attachments
        </div>
        <div class="card-body">
          
            <table class="table table-border table-bordered">
                <thead>
                    <tr>
                        <th scope="col">SN</th>
                        <th scope="col">Document Name</th>
                        <th scope="col">Document</th>
                    </tr>
                </thead>
                @foreach ($attachments as $i => $attachment)
                    <tr>
                        <td>
                            {{ $i + 1 }}
                        </td>
                        <td>
                            <div class="input-group @error('attachment.' . $i) is-invalid @enderror">
                                <input class="form-control @error('attachments.' . $i . '.name') is-invalid @enderror"
                                    wire:model.lazy="attachments.{{ $i }}.name" />
                                @error('attachments.' . $i . '.name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </td>
                        <td>
                            <div class="input-group @error('attachment.' . $i) is-invalid @enderror">
                                <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                                    x-on:livewire-upload-finish="isUploading = false"
                                    x-on:livewire-upload-error="isUploading = false"
                                    x-on:livewire-upload-progress="progress = $event.detail.progress">

                                    <input
                                        class="form-control @error('attachments.' . $i . '.file') is-invalid @enderror"
                                        wire:model.lazy="attachments.{{ $i }}.file" type="file"
                                        accept="application/pdf" />

                                    <!-- Progress Bar -->
                                    <div x-show="isUploading">
                                        <progress max="100" x-bind:value="progress"></progress>
                                    </div>
                                </div>

                                @error('attachments.' . $i . '.file')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="text-secondary small">
                                <span class="font-weight-bold">
                                    {{ __('Note') }}:
                                </span>
                                    <span class="">
                                    {{ __('Uploaded Documents must be less than 3  MB in size') }}
                                </span>
                            </div>
                        </td>

                        <td style="min-width: 100%">
                            @if (count($attachments) > 1)
                                <div class="text-right mt-2">
                                    <button class="btn btn-danger btn-sm"
                                        wire:click="removeAttachment({{ $i }})">
                                        <i class="bi bi-x-lg mr-1"></i>
                                        <small> Remove </small>
                                    </button>
                                </div>
                            @endif
                        </td>

                    </tr>
                @endforeach

            </table>
            <div class="text-right mt-2">
                <button class="btn btn-secondary" wire:click="addAttachment()">
                    <i class="bi bi-plus-circle mr-1"></i>
                    Add Attachment
                </button>
            </div>
        </div>
    </div>
    <div class="row pt-4">
        <div class="col-12 text-right">
            <button class="btn btn-primary" wire:click="save()">
                <i class="bi bi-arrow-return-right mr-2"></i>
                Save
            </button>
        </div>
    </div>
</div>
