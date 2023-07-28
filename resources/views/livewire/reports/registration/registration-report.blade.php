<div>
    <!-- report type -->
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card-header"> <b> Report Type</b></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>
                        Criteria
                    </span>
                </label>
                <select wire:model="reportType"
                    class="form-control {{ $errors->has('reportType') ? 'is-invalid' : '' }}">
                    <option value="all">All Registered Business</option>
                    @foreach ($optionReportTypes as $key => $report)
                    <option value={{ $key }}>
                        {{ $report }}</option>
                    @endforeach
                </select>
                @error('reportType')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            @if ($reportType == 'Business-Reg-By-Nature')
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>
                        ISIC LEVEL I
                    </span>
                </label>
                <select wire:model="isic1Id" class="form-control {{ $errors->has('isic1Id') ? 'is-invalid' : '' }}"
                    multiple>
                    <option value="">Select Level</option>
                    @foreach ($optionIsic1s as $isic1)
                    <option value={{ $isic1->id }}>
                        {{ $isic1->description }}</option>
                    @endforeach
                </select>
                @error('isic1Id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>
                        ISIC LEVEL II
                    </span>
                </label>
                <select wire:model="isic2Id" class="form-control {{ $errors->has('isic2Id') ? 'is-invalid' : '' }}"
                    multiple>
                    <option value="">Select Level</option>
                    @foreach ($optionIsic2s as $isic2)
                    <option value={{ $isic2->id }}>
                        {{ $isic2->description }}</option>
                    @endforeach
                </select>
                @error('isic2Id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>
                        ISIC LEVEL III
                    </span>
                </label>
                <select wire:model="isic3Id" class="form-control {{ $errors->has('isic3Id') ? 'is-invalid' : '' }}"
                    multiple>
                    <option value="">Select Level</option>
                    @foreach ($optionIsic3s as $isic3)
                    <option value={{ $isic3->id }}>
                        {{ $isic3->description }}</option>
                    @endforeach
                </select>
                @error('isic3Id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>
                        ISIC LEVEL IV
                    </span>
                </label>
                <select wire:model="isic4Id" class="form-control {{ $errors->has('isic4Id') ? 'is-invalid' : '' }}"
                    multiple>
                    <option value="">Select Level</option>
                    @foreach ($optionIsic4s as $isic4)
                    <option value={{ $isic4->id }}>
                        {{ $isic4->description }}</option>
                    @endforeach
                </select>
                @error('isic4Id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            @endif

            @if ($reportType == 'Business-Reg-By-TaxType')
            <div class="col-md-4 form-group">
                <label for="report_type" class="d-flex justify-content-between'">
                    <span>
                        Select Tax Type
                    </span>
                </label>
                <select wire:model="tax_type_id"
                    class="form-control {{ $errors->has('tax_type_id') ? 'is-invalid' : '' }}">
                    <option value="">Select Tax Type</option>
                    <option value="all">All</option>
                    @foreach ($optionTaxTypes as $tax)
                    <option value={{ $tax->id }}>
                        {{ $tax->name }}</option>
                    @endforeach
                </select>
                @error('tax_type_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            @endif

            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>
                        Year
                    </span>
                </label>
                <select wire:model="year" class="form-control {{ $errors->has('year') ? 'is-invalid' : '' }}">
                    <option value="all">All</option>
                    <option value="range">Custome Range</option>
                    @foreach ($optionYears as $key => $y)
                    <option value={{ $y }}>
                        {{ $y }}</option>
                    @endforeach
                </select>
                @error('year')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            @if ($year != 'all' && $year != 'range')
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>
                        Month
                    </span>
                </label>
                <select wire:model="month" class="form-control {{ $errors->has('month') ? 'is-invalid' : '' }}">
                    <option value="all">All</option>
                    @foreach ($optionMonths as $key => $m)
                    <option value={{ $key }}>
                        {{ $m }}</option>
                    @endforeach
                </select>
                @error('month')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            @endif

            @if ($year == 'range')
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>Start Date</span>
                </label>
                <input type="date" class="form-control" wire:model="range_start">
                @error('range_start')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>End Date</span>
                </label>
                <input type="date" class="form-control" wire:model="range_end">
                @error('range_end')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            @endif

            <!-- Show more filters -->
            <div class="col-md-4 form-group">
                <button class="btn btn-outline-primary btn-xs ml-2 mt-4" wire:click="toggleFilters">

                    @if ($showMoreFilters)
                    <i class="bi bi-filter mr-3"></i>
                    Hide More filters
                    @else
                    <i class="bi bi-filter"></i>
                    More Filters...
                    @endif
                </button>
            </div>

        </div>
    </div>

    
 


    @if ($showMoreFilters)
    <!-- tax region -->
    <div>
        <div class="row pt-4">
            <div class="col-12">
                <div class="card-header"><b>Tax Region</b></div>
            </div>
        </div>
        <div class="row">
            @foreach ($optionTaxRegions as $id => $taxRegion)
            <div class="col-sm-2 form-group">
                <label class="d-flex justify-content-between" for="tax-region-{{ $id }}">
                    <span>
                        {{ $taxRegion }}
                    </span>
                </label>
                <input type="checkbox" wire:model="selectedTaxReginIds.{{ $id }}" id="tax-region-{{ $id }}">
            </div>
            @endforeach
        </div>
    </div>
    <!-- Physical Location -->
    <div>
        <div class="row pt-2">
            <div class="col-12">
                <div class="card-header"><b>Physical Location</b></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label>Region</label>
                <select wire:model="region" class="form-control @error('region') is-invalid @enderror">
                    <option value='all'>ALL</option>
                    @foreach ($regions as $reg)
                    <option value="{{ $reg->id }}">{{ $reg->name }}</option>
                    @endforeach
                </select>
            </div>
            @if ($region != 'all')
            <div class="col-md-4 form-group">
                <label>District</label>
                <select wire:model="district" class="form-control @error('district') is-invalid @enderror">
                    <option value='all'>ALL</option>
                    @foreach ($districts as $dist)
                    <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            @if ($district != 'all')
            <div class="col-md-4 form-group">
                <label><span>Ward</span></label>
                <select wire:model="ward" class="form-control @error('ward') is-invalid @enderror">
                    <option value='all'>ALL</option>
                    @foreach ($wards as $war)
                    <option value="{{ $war->id }}">{{ $war->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

        </div>
    </div>
    <!-- Business Category -->
    <div>
        <div class="row pt-2">
            <div class="col-12">
                <div class="card-header"><b>Business Category</b></div>
            </div>
        </div>
        <div class="row">
            @foreach ($optionBusinessCategories as $id => $businessCategory)
            <div class="col-sm-2 form-group">
                <label class="d-flex justify-content-between" for="business-category-{{ $id }}">
                    <span>
                        {{ $businessCategory }}
                    </span>
                </label>
                <input type="checkbox" wire:model="selectedBusinessCategoryIds.{{ $id }}"
                    id="business-category-{{ $id }}">
            </div>
            @endforeach
        </div>
    </div>
    <!-- Business Activity -->
    <div>
        <div class="row pt-2">
            <div class="col-12">
                <div class="card-header"><b>Business Activity Type</b></div>
            </div>
        </div>
        <div class="row">
            @foreach ($optionBusinessActivities as $id => $businessActivity)
            <div class="col-sm-2 form-group">
                <label class="d-flex justify-content-between" for="business-activity-{{ $id }}">
                    <span>
                        {{ $businessActivity }}
                    </span>
                </label>
                <input type="checkbox" wire:model="selectedBusinessActivityIds.{{ $id }}"
                    id="business-activity-{{ $id }}">
            </div>
            @endforeach
        </div>
    </div>

    <div>
        <div class="row pt-2">
            <div class="col-12">
                <div class="card-header"><b>Business Consultant Type</b></div>
            </div>
        </div>
        <div class="row">
            @foreach ($optionBusinessConsultants as $id => $businessConsultant)
            <div class="col-sm-2 form-group">
                <label class="d-flex justify-content-between" for="business-consultant-{{ $id }}">
                    <span>
                        {{ $businessConsultant }}
                    </span>
                </label>
                <input type="checkbox" wire:model="selectedBusinessConsultants.{{ $id }}"
                    id="business-consultant-{{ $id }}">
            </div>
            @endforeach
        </div>
    </div>
    @endif


    <div class="row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <button class="btn btn-primary ml-2" wire:click="preview" wire:loading.attr="disabled">
                <i class="bi bi-funnel ml-1" wire:loading.remove wire:target="priview"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="priview"></i>
                Search
            </button>
            @if ($hasData)
            <button class="btn btn-success ml-2" wire:click="exportExcel" wire:loading.attr="disabled">
                <i class="bi bi-file-earmark-spreadsheet ml-1" wire:loading.remove wire:target="exportExcel"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="exportExcel"></i>
                Export to Excel
            </button>
            <button class="btn btn-info ml-2" wire:click="exportPdf" wire:loading.attr="disabled">
                <i class="bi bi-file-earmark-pdf ml-1" wire:loading.remove wire:target="exportPdf"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="exportPdf"></i>
                Export to Pdf
            </button>
            @endif
        </div>
    </div>

    @if($hasData)
    <div class="mt-3">
        @livewire('reports.business.preview-table',['parameters'=>$parameters])
    </div>
    @endif

</div>