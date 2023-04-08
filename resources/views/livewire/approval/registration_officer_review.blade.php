<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">ISIIC Configurations</div>
            <div class="card-body row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Select ISIIC I</label>
                        <select class="form-control @error('isiic_i') is-invalid @enderror"
                            wire:change="isiiciChange($event.target.value)" wire:model="isiic_i">
                            <option value="null" disabled selected>Select</option>
                            @foreach ($isiiciList as $row)
                                <option value="{{ $row->id }}">{{ $row->description }}</option>
                            @endforeach
                        </select>
                        @error('isiic_i')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 ">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Select ISIIC II</label>
                        <select class="form-control @error('isiic_ii') is-invalid @enderror"
                            wire:change="isiiciiChange($event.target.value)" wire:model="isiic_ii">
                            <option value='null' disabled selected>Select</option>
                            @foreach ($isiiciiList as $row)
                                <option value="{{ $row->id }}">{{ $row->description }}</option>
                            @endforeach
                        </select>
                        @error('isiic_ii')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 ">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Select ISIIC III</label>
                        <select class="form-control @error('isiic_iii') is-invalid @enderror"
                            wire:change="isiiciiiChange($event.target.value)" wire:model="isiic_iii">
                            <option value="null" disabled selected>Select</option>
                            @foreach ($isiiciiiList as $row)
                                <option value="{{ $row->id }}">{{ $row->description }}</option>
                            @endforeach
                        </select>
                        @error('isiic_iii')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 ">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Select ISIIC IV</label>
                        <select class="form-control @error('isiic_iv') is-invalid @enderror" wire:model="isiic_iv">
                            <option value="null" disabled selected>Select</option>
                            @foreach ($isiicivList as $row)
                                <option value="{{ $row->id }}">{{ $row->description }}</option>
                            @endforeach
                        </select>
                        @error('isiic_iv')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 ">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Tax Region</label>
                        <select class="form-control @error('selectedTaxRegion') is-invalid @enderror"
                            wire:model.defer="selectedTaxRegion">
                            <option value="null" disabled selected>Select</option>
                            @foreach ($taxRegions as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedTaxRegion')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="effectiveDate">Effective Date</label>
                        <input id="effectiveDate" type="date" class="form-control @error('effectiveDate') is-invalid @enderror"
                                wire:model="effectiveDate" required />
                        @error('effectiveDate')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Business Type Configurations</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" wire:model.lazy="isBusinessElectric">
                                Is Business Electric
                            </label>
                            @error('isBusinessElectric')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" wire:model.lazy="isBusinessLTO">
                                Is Business LTO
                            </label>
                            @error('isBusinessLTO')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Tax Type Configurations</div>
            <div class="card-body">
                    @foreach ($selectedTaxTypes as $key => $value)
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Tax Type</label>
                                <select
                                    class="form-control @error("selectedTaxTypes.{$key}.tax_type_id") is-invalid @enderror"
                                    wire:model="selectedTaxTypes.{{ $key }}.tax_type_id">
                                    <option value="" selected disabled>--Select---</option>
                                    @foreach ($taxTypes as $type)
                                        <option value="{{ $type->id }}" @if ($type->id === $selectedTaxTypes[$key]['tax_type_id']) disabled @endif>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error("selectedTaxTypes.{$key}.tax_type_id")
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Currency</label>
                                <select
                                    class="form-control @error("selectedTaxTypes.{$key}.currency") is-invalid @enderror"
                                    wire:model="selectedTaxTypes.{{ $key }}.currency">
                                    <option value="" selected disabled>--Select---</option>
                                    <option value="TZS">Tanzania Shillings</option>
                                    <option value="USD">United State Dollar</option>
                                </select>
                                @error("selectedTaxTypes.{$key}.currency")
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        @if (!empty($selectedTaxTypes[$key]['tax_type_id']) && $selectedTaxTypes[$key]['tax_type_id'] == $vat_id)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">VAT Category Type</label>
                                    <div class="position-relative">
                                        <input type="hidden" wire:model="selectedTaxTypes.{{ $key }}.sub_vat_id">
                                        <input type="text" class="form-control" placeholder="Search..." wire:model="selectedTaxTypes.{{ $key }}.sub_vat_name" wire:keyup="subCategorySearchUpdate({{$key}}, $event.target.value)">
                                        @if($selectedTaxTypes[$key]['show_hide_options'])
                                            <div class="position-absolute" style="z-index: 1" wire:loading wire:target="subCategorySearchUpdate">
                                                Loading....
                                            </div>
                                            <div wire:loading.remove>
                                                @if(count($subVatOptions) > 0)
                                                    <div class="position-absolute border-bottom rounded" style="overflow: hidden;z-index: 1;">
                                                        <ul class="custom-dropdown-menu pb-2">
                                                            @foreach($subVatOptions as $sub)
                                                                <li>
                                                                    <a type="button" wire:click="selectSubVat({{$key}}, {{$sub}})">
                                                                        <small>{{ $sub->name }}</small>
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @else
                                                    <ul class="custom-dropdown-menu pb-2">
                                                        <li>
                                                            <small class="font-italic text-center">No record match!</small>
                                                        </li>
                                                    </ul>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    @error("selectedTaxTypes.{{ $key }}.sub_vat_id")
                                    <span class="text-danger error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        @if ($showLumpsumOptions === true)
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">Annual Estimate</label>
                                    <input type="number"
                                        class="form-control @error("selectedTaxTypes.{$key}.annual_estimate") is-invalid @enderror"
                                        wire:model="selectedTaxTypes.{{ $key }}.annual_estimate">
                                    @error("selectedTaxTypes.{$key}.annual_estimate")
                                        <span class="text-danger error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">Payment Quarters per year </label>

                                    <select
                                        class="form-control @error("selectedTaxTypes.{$key}.quarters") is-invalid @enderror"
                                        wire:model="selectedTaxTypes.{{ $key }}.quarters">
                                        <option value="" selected disabled>--Select---</option>
                                        <option value="1">One </option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                        <option value="4">Four</option>
                                        <option value="5">Five</option>
                                        <option value="6">Six</option>
                                        <option value="7">Seven</option>
                                        <option value="8">Eight</option>
                                        <option value="9">Nine</option>
                                        <option value="10">Ten</option>
                                        <option value="11">Eleven</option>
                                        <option value="12">Twelve</option>
                                    </select>

                                    @error("selectedTaxTypes.{$key}.quarters")
                                        <span class="text-danger error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <div class="d-flex align-items-center">
                            @if ($key > 0)
                                <button class="btn btn-danger btn-sm"
                                    wire:click.prevent="removeTaxType({{ $key }})">
                                    Remove
                                </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
            </div>
            @if (!$showLumpsumOptions)
                <div class="card-footer">
                    <button class="btn text-white btn-info" wire:click.prevent="addTaxtype()">
                        <i class="bi bi-plus-square-fill"></i>
                        Add Tax Type
                    </button>
                </div>
            @endif
        </div>
    </div>

</div>
