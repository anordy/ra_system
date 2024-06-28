<div class="col-md-12">
    @if(count($currentTaxTypes) > 0)
        <div class="card rounded-0 shadow-none border">
            @include('layouts.component.messages')
            <div class="card-header bg-white font-weight-bold">Old Tax Type Configurations</div>
            <div class="card-body">
                @foreach ($currentTaxTypes as $key => $value)
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Tax Type</label>
                                <input type="text" disabled class="form-control" value="{{ getTaxTypeName($value['tax_type_id'])  }}"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Currency</label>
                                <input type="text" disabled class="form-control" value="{{ $value['currency']  }}"/>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    @endif
    <div class="card rounded-0 shadow-none border">
        <div class="card-header bg-white font-weight-bold">Tax Type Configurations</div>
        <div class="card-body">
            @foreach ($selectedTaxTypes as $key => $value)
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Tax Type</label>
                            <select
                                    class="form-control @error("selectedTaxTypes.{$key}.tax_type_id") is-invalid @enderror"
                                    wire:model="selectedTaxTypes.{{ $key }}.tax_type_id" disabled>
                                <option value="" selected disabled>--Select---</option>
                                @foreach ($taxTypes as $type)
                                    <option value="{{ $type->id }}" >{{ $type->name }}</option>
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
                                    wire:model="selectedTaxTypes.{{ $key }}.currency" disabled>
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
                                    <input type="text" disabled class="form-control" placeholder="Search..." wire:model="selectedTaxTypes.{{ $key }}.sub_vat_name" wire:keyup="subCategorySearchUpdate({{$key}}, $event.target.value)">
                                    @if($selectedTaxTypes[$key]['show_hide_options'])
                                        <div class="position-absolute z-index-1" wire:loading wire:target="subCategorySearchUpdate">
                                            Loading....
                                        </div>
                                        <div wire:loading.remove>
                                            @if($subVatOptions)
                                                <div class="position-absolute border-bottom rounded sub-vat-items">
                                                    <ul class="customized-dropdown-menu pb-2">
                                                        @foreach($subVatOptions as $sub)
                                                            <li wire:click="selectSubVat({{$key}}, {{$sub}})">
                                                                <small>{{ $sub->name }}</small>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @else
                                                <ul class="customized-dropdown-menu pb-2">
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

                    @if($lumpsumPayment)
                            <div class="col-md-5 mb-3">
                                <span class="font-weight-bold text-uppercase">Annual Estimate</span>
                                <p class="my-1">{{ $lumpsumPayment['annual_estimate'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Payment Quarters</span>
                                <p class="my-1">{{ $lumpsumPayment['payment_quarters'] ?? '' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Currency</span>
                                <p class="my-1">{{ $lumpsumPayment['currency'] ?? 'N/A' }}</p>
                            </div>
                    @endif

                    @if ($showLumpsumOptions === true)
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="form-label">Annual Sales</label>
                                <select
                                        class="form-control @error("selectedTaxTypes.{$key}.annual_estimate") is-invalid @enderror"
                                        wire:model="selectedTaxTypes.{{ $key }}.annual_estimate">
                                    <option value="" selected disabled>--Select---</option>
                                    @foreach($annualSales as $annualSale)
                                        <option value="{{$annualSale['payments_per_year']}}"> {{number_format($annualSale['min_sales_per_year'],2)}} - {{ number_format($annualSale['max_sales_per_year'],2)}} </option>
                                    @endforeach
                                </select>
                                @error("selectedTaxTypes.{$key}.annual_estimate")
                                <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @foreach ($selectedTaxTypes as $key => $selectedTaxType)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Payment per year </label>
                                    <input disabled type="text" value="{{number_format((int)$selectedTaxType['annual_estimate'] )}}" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Payment per three months </label>
                                    <input disabled type="text" value="{{number_format((int)$selectedTaxType['annual_estimate']/4)}}" class="form-control" disabled>
                                </div>
                            </div>
                        @endforeach

                    @endif

                </div>
            @endforeach
        </div>
    </div>
</div>
