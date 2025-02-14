<div class="card mt-3">
    <div class="card-header font-weight-bold bg-white">
        {{ __('Chassis Information') }}
    </div>
    <div class="card-body">
        <div class="row my-2">
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Chassis Number</span>
                <p class="my-1">{{ $motor_vehicle->chassis_number ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Year</span>
                <p class="my-1">{{ $motor_vehicle->vehicle_manufacture_year ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">imported from</span>
                <p class="my-1">{{ $motor_vehicle->imported_from ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Engine Number</span>
                <p class="my-1">{{ $motor_vehicle->engine_number ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Engine Capacity (cc)</span>
                <p class="my-1">{{ $motor_vehicle->engine_capacity ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Transmission Type</span>
                <p class="my-1">{{ $motor_vehicle->transmissionTypeTra->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Vehicle Category</span>
                <p class="my-1">{{ $motor_vehicle->categoryTypeTra->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Fuel type</span>
                <p class="my-1">{{ $motor_vehicle->fuelTypeTra->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Make</span>
                <p class="my-1">{{ $motor_vehicle->makeTypeTra->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Model Type</Span>
                <p class="my-1">{{ $motor_vehicle->modelTypeTra->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Model Number</Span>
                <p class="my-1">{{ $motor_vehicle->modelNumberTra->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase"> TANSAD number</span>
                <p class="my-1">{{ $motor_vehicle->tansad_number ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Gross weight</span>
                <p class="my-1">{{ $motor_vehicle->gross_weight ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Tare weight</span>
                <p class="my-1">{{ $motor_vehicle->tare_weight ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Color</span>
                <p class="my-1">{{ $motor_vehicle->colorTypeTra->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Usage Type</span>
                <p class="my-1">{{ $motor_vehicle->usageTypeTra->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Purchase Day</Span>
                <p class="my-1">{{ $motor_vehicle->purchase_day ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Passenger Capacity</span>
                <p class="my-1">{{ $motor_vehicle->passenger_capacity ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Owner Category</Span>
                <p class="my-1">{{ $motor_vehicle->ownerCategoryTra->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Importer Name</span>
                <p class="my-1">{{ $motor_vehicle->importer_name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">TIN</span>
                <p class="my-1">{{ $motor_vehicle->importer_tin ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>