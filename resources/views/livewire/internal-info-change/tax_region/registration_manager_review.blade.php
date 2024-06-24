
<div class="card rounded-0 shadow-none border">
    @include('layouts.component.messages')
    <div class="card-body">
        <div class="row pr-3 pl-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Current Tax Department</label>
                    <select disabled class="form-control @error('currentDepartmentId') is-invalid @enderror"
                            wire:model.defer="currentDepartmentId"
                            wire:change="selectedDepartment($event.target.value) ">
                        <option value="null" disabled selected>Select</option>
                        @foreach ($taxDepartment as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('currentDepartmentId')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">New Tax Department</label>
                    <select class="form-control @error('selectedDepartment') is-invalid @enderror"
                            wire:model.defer="selectedDepartment"
                            wire:change="selectedDepartment($event.target.value) ">
                        <option value="null" disabled selected>Select</option>
                        @foreach ($taxDepartment as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedDepartment')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row pr-3 pl-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Current Tax Region</label>
                    <select disabled class="form-control @error('currentTaxRegionId') is-invalid @enderror"
                            wire:model.defer="currentTaxRegionId">
                        <option value="null" disabled selected>Select</option>
                        @foreach ($taxRegions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                        @endforeach
                    </select>
                    @error('taxRegionId')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">New Tax Region</label>
                    <select class="form-control @error('taxRegionId') is-invalid @enderror"
                            wire:model.defer="taxRegionId">
                        <option value="null" disabled selected>Select</option>
                        @foreach ($taxRegions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                        @endforeach
                    </select>
                    @error('taxRegionId')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

        </div>

    </div>
</div>