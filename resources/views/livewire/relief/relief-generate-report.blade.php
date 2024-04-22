<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    {{-- @include('layouts.component.messages') --}}
    <div class="shadow rounded">
        <div class="card pt-2">
            <div class="card-header text-uppercase font-weight-bold bg-grey ">
                Report Type
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="report_type" class="d-flex justify-content-between'">
                            <span>
                                Report Type
                            </span>
                        </label>
                        <select id="report_type" wire:model="reportType"
                            class="form-control {{ $errors->has($reportType) ? 'is-invalid' : '' }}">
                            @foreach ($optionReportTypes as $key => $reportType)
                                <option value="{{ $key }}">
                                    {{ $reportType }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($showProjectSections)
                        <div class="col-md-4 form-group">
                            <label for="project_section" class="d-flex justify-content-between'">
                                <span>
                                    Project Section
                                </span>
                            </label>
                            <select id="project_section" wire:model="projectSectionId"
                                class="form-control {{ $errors->has($projectSectionId) ? 'is-invalid' : '' }}">
                                <option value="all">All</option>
                                @foreach ($optionProjectSections as $projectSection)
                                    <option value="{{ $projectSection->id }}">
                                        {{ $projectSection->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if ($showProjects)
                        <div class="col-md-4 form-group">
                            <label for="projects" class="d-flex justify-content-between'">
                                <span>
                                    Project
                                </span>
                            </label>
                            <select id="projects" wire:model="projectId"
                                class="form-control {{ $errors->has($projectId) ? 'is-invalid' : '' }}">
                                <option value="all">All</option>
                                @foreach ($optionProjects as $project)
                                    <option value="{{ $project->id }}">
                                        {{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if ($showMinistries)
                        <div class="col-md-4 form-group">
                            <label for="ministries" class="d-flex justify-content-between'">
                                <span>
                                    Ministry
                                </span>
                            </label>
                            <select id="ministries" wire:model="ministryId"
                                class="form-control {{ $errors->has($ministryId) ? 'is-invalid' : '' }}">
                                <option value="all">All-With-Ministry</option>
                                <option value="without">All-Without-Ministry</option>
                                @foreach ($optionMinistries as $ministry)
                                    <option value="{{ $ministry->id }}">
                                        {{ $ministry->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if ($showSuppliers)
                        <div class="col-md-4 form-group">
                            <label for="Suppliers" class="d-flex justify-content-between'">
                                <span>
                                    Supplier
                                </span>
                            </label>
                            <select id="Suppliers" wire:model="supplierId"
                                class="form-control {{ $errors->has($supplierId) ? 'is-invalid' : '' }}">
                                <option value="all">All</option>
                                @foreach ($optionSuppliers as $supplier)
                                    <option value="{{ $supplier->id }}">
                                        {{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if ($showSuppliersLocations)
                        <div class="col-md-4 form-group">
                            <label for="SuppliersLocations" class="d-flex justify-content-between'">
                                <span>
                                    Location
                                </span>
                            </label>
                            <select id="SuppliersLocations" wire:model="supplierLocationId"
                                class="form-control {{ $errors->has($supplierLocationId) ? 'is-invalid' : '' }}">
                                <option value="all">All</option>
                                @foreach ($optionSupplierLocations as $supplierLocation)
                                    <option value="{{ $supplierLocation->id }}">
                                        {{ $supplierLocation->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if ($showSponsors)
                        <div class="col-md-4 form-group">
                            <label for="Sponsors" class="d-flex justify-content-between'">
                                <span>
                                    Sponsors
                                </span>
                            </label>
                            <select id="Sponsors" wire:model="sponsorId"
                                class="form-control {{ $errors->has($sponsorId) ? 'is-invalid' : '' }}">
                                <option value="all">All-With-Sponsor</option>
                                <option value="without">All-Without-Sponsor</option>
                                @foreach ($optionSponsors as $sponsor)
                                    <option value="{{ $sponsor->id }}">
                                        {{ $sponsor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                </div>
            </div>
            <div class="card-header text-uppercase font-weight-bold bg-grey ">
                Report Period
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="start_month" class="d-flex justify-content-between'">
                            <span>
                                Year
                            </span>
                        </label>
                        <select name="year" id="start_month" wire:model="year"
                            class="form-control {{ $errors->has($year) ? 'is-invalid' : '' }}" {{-- wire:changed="preview" --}}>
                            @foreach ($optionYears as $optionYear)
                                <option value="{{ $optionYear }}">
                                    {{ $optionYear }}</option>
                            @endforeach
                        </select>
                        @error('year')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    @if ($showOptions)
                        <div class="col-md-4 form-group">
                            <label for="Period" class="d-flex justify-content-between'">
                                <span>
                                    Period
                                </span>
                            </label>
                            <select name="period" id="Period" wire:model="period"
                                class="form-control {{ $errors->has($period) ? 'is-invalid' : '' }}">
                                <option value="" disabled>Select Period</option>
                                @foreach ($optionPeriods as $optionPeriod)
                                    <option value="{{ $optionPeriod }}">
                                        {{ $optionPeriod }}</option>
                                @endforeach
                            </select>
                            @error('period')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @if ($showSemiAnnuals)
                            <div class="col-md-4 form-group">
                                <label for="Quarter" class="d-flex justify-content-between'">
                                    <span>
                                        Semi-Annual
                                    </span>
                                </label>
                                <select name="semiAnnual" id="Quarter" wire:model="semiAnnual"
                                    class="form-control {{ $errors->has($semiAnnual) ? 'is-invalid' : '' }}">
                                    <option value="" disabled>Select Semi-Annual term</option>
                                    @foreach ($optionSemiAnnuals as $optionSemiAnnual)
                                        <option value={{ $optionSemiAnnual }}>
                                            {{ $optionSemiAnnual }}</option>
                                    @endforeach
                                </select>
                                @error('semiAnnual')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif
                        @if ($showQuarters)
                            <div class="col-md-4 form-group">
                                <label for="Quarter" class="d-flex justify-content-between'">
                                    <span>
                                        Quarter
                                    </span>
                                </label>
                                <select name="quater" id="Quarter" wire:model="quater"
                                    class="form-control {{ $errors->has($quater) ? 'is-invalid' : '' }}">
                                    <option value="" disabled>Select Quarter</option>
                                    @foreach ($optionQuarters as $optionQuarter)
                                        <option value={{ $optionQuarter }}>
                                            {{ $optionQuarter }}</option>
                                    @endforeach
                                </select>
                                @error('quater')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif
                        @if ($showMonths)
                            <div class="col-md-4 form-group">
                                <label for="Month" class="d-flex justify-content-between'">
                                    <span>
                                        Months
                                    </span>
                                </label>
                                <select name="month" id="Month" wire:model="month"
                                    class="form-control {{ $errors->has($month) ? 'is-invalid' : '' }}">
                                    <option value="" disabled>Select Month</option>
                                    @foreach ($optionMonths as $key => $optionMonth)
                                        <option value={{ $key }}>
                                            {{ $optionMonth }}</option>
                                    @endforeach
                                </select>
                                @error('month')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif
                    @endif
                </div>

                <div class="d-flex justify-content-start mt-3 ">
                   
                    <div class="d-flex justify-content-end w-100">
                        <div x-data>
                            <button class="btn btn-warning ml-2" wire:click="preview">
                                <i class="bi bi-eye-fill"></i>
                                Preview Report
                            </button>
                        </div>
                        <button class="btn btn-success ml-2" wire:click="export " wire:loading.attr="disabled">
                            <i class="bi bi-file-earmark-spreadsheet ml-1" wire:loading.remove
                                wire:target="export"></i>
                            <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                                wire:target="export"></i>
                            Export to Excel
                        </button>
                        <button class="btn btn-success ml-2" wire:click="exportPdf" wire:loading.attr="disabled">
                            <i class="bi bi-file-pdf ml-1" wire:loading.remove wire:target="exportPdf"></i>
                            <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                                wire:target="exportPdf"></i>
                            Export to PDF
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    

</div>
