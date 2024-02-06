@extends('layouts.master')

@section('title', 'Chassis Number Details')

@section('content')
    <div class="card mt-3">
        <div class="card-header bg-white font-weight-bold">
            Chassis Details
        </div>
        <div class="card-body">
                <div class="row my-2">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Chassis Number</span>
                        <p class="my-1">{{ $chassis->chassis_number }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Year</span>
                        <p class="my-1">{{ $chassis->year ?? '' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">imported from</span>
                        <p class="my-1">{{ $chassis->imported_from }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Engine Number</span>
                        <p class="my-1">{{ $chassis->engine_number }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Engine capacity (cc)</span>
                        <p class="my-1">{{ $chassis->engine_cubic_capacity }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Class</span>
                        <p class="my-1">{{ $motor_vehicle->class->name ?? '' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Fuel type</span>
                        <p class="my-1">{{ $chassis->fuel_type }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Transmission type</span>
                        <p class="my-1">{{ $chassis->transmission_type }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Passenger Capacity</span>
                        <p class="my-1">{{ $chassis->passenger_capacity }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Make</span>
                        <p class="my-1">{{ $chassis->make }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Model Number</Span>
                        <p class="my-1">{{ $chassis->model_number ?? '' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Model</Span>
                        <p class="my-1">{{ $chassis->model_type ?? '' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Body Type</Span>
                        <p class="my-1">{{ $chassis->body_type ?? '' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase"> Custom number</span>
                        <p class="my-1">{{ $chassis->tansad_number }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Tare weight</span>
                        <p class="my-1">{{ $chassis->tare_weight }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Gross weight</span>
                        <p class="my-1">{{ $chassis->gross_weight }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Color</span>
                        <p class="my-1">{{ $chassis->color }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Usage Type</span>
                        <p class="my-1">{{ $chassis->usage_type }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Owner Category</span>
                        <p class="my-1">{{ $chassis->owner_category }}</p>
                    </div>
        </div>
    </div>
    </div>

        <div class="card mt-3">
            <div class="card-header bg-white font-weight-bold">
                Importer Details
            </div>
            <div class="card-body">
                <div class="row my-2">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Importer's Name</span>
                        <p class="my-1">{{ $chassis->importer_name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Importer's TIN</span>
                        <p class="my-1">{{ $chassis->importer_tin ?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>

@endsection
