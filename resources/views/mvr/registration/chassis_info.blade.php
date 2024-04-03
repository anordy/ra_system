<div class="card mt-3">
    <div class="card-header font-weight-bold bg-white">
        {{ __('Chassis Information') }}
    </div>
    <div class="card-body">
        <div class="row my-2">
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Chassis Number</span>
                <p class="my-1">{{ $motor_vehicle['chassis_number'] }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Year</span>
                <p class="my-1">{{ $motor_vehicle['year'] ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">imported from</span>
                <p class="my-1">{{ $motor_vehicle['imported_from'] ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Engine capacity (cc)</span>
                <p class="my-1">{{ $motor_vehicle['engine_cubic_capacity'] ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Class</span>
                <p class="my-1">{{ $motor_vehicle['class'] ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Fuel type</span>
                <p class="my-1">{{ $motor_vehicle['fuel_type'] ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Make</span>
                <p class="my-1">{{ $motor_vehicle['make'] ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Model Type</Span>
                <p class="my-1">{{ $motor_vehicle['model_type'] }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Model Number</Span>
                <p class="my-1">{{ $motor_vehicle['model_number'] }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase"> TANSAD number</span>
                <p class="my-1">{{ $motor_vehicle['tansad_number'] }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Gross weight</span>
                <p class="my-1">{{ $motor_vehicle['gross_weight'] }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Color</span>
                <p class="my-1">{{ $motor_vehicle['color'] ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Usage Type</span>
                <p class="my-1">{{ $motor_vehicle['usage_type'] }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Vehicle Category</Span>
                <p class="my-1">{{ $motor_vehicle['vehicle_category'] ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Purchase Day</Span>
                <p class="my-1">{{ $motor_vehicle['purchase_day'] }}</p>
            </div>

            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Passenger Capacity</span>
                <p class="my-1">{{ $motor_vehicle['passenger_capacity'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header font-weight-bold bg-white">
        {{ __('Importer/Owner Information') }}
    </div>
    <div class="card-body">
        <div class="row my-2">
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Owner Category</Span>
                <p class="my-1">{{ $motor_vehicle['owner_category'] }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Name</span>
                <p class="my-1">{{ $motor_vehicle['importer_name'] }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">TIN</span>
                <p class="my-1">{{ $motor_vehicle['importer_tin'] }}</p>
            </div>
        </div>
    </div>
</div>