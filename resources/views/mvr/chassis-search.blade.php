@extends('layouts.master')

@section('title', 'Motor Vehicle Search')

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <div class="card-header">
                <h5 class="text-uppercase">Chassis #: {{$chassis}}</h5>
                <div class="card-tools p-3">
                    <button class="btn btn-info btn-sm"
                            onclick="Livewire.emit('showModal', 'mvr.chassis-number-search','mvr.chassis-search')"><i
                                class="fa fa-search"></i>
                        New Search</button>
                    <button class="btn btn-primary btn-sm"
                            onclick="Livewire.emit('showModal', 'mvr.upload-inspection-report','{{$chassis}}')"><i
                                class="fa fa-arrow-right"></i>
                        Proceed With Registration</button>
                </div>
            </div>

            @if(!empty($motor_vehicle))
                <div class="card mt-3">
                <div class="card-body">
                    <div class="row my-2">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Chassis Number</span>
                            <p class="my-1">{{ $motor_vehicle['chassis_number'] }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Year</span>
                            <p class="my-1">{{ $motor_vehicle['year'] }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">imported from</span>
                            <p class="my-1">{{ $motor_vehicle['imported_from'] }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Engine capacity</span>
                            <p class="my-1">{{ $motor_vehicle['engine_capacity'] }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Class</span>
                            <p class="my-1">{{ $motor_vehicle['class'] }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Fuel type</span>
                            <p class="my-1">{{ $motor_vehicle['fuel_type'] }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Make</span>
                            <p class="my-1">{{ $motor_vehicle['make'] }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Model</Span>
                            <p class="my-1">{{ $motor_vehicle['model'] }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase"> Custom number</span>
                            <p class="my-1">{{ $motor_vehicle['custom_number'] }}</p>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Gross weight</span>
                            <p class="my-1">{{ $motor_vehicle['gross_weight'] }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Color</span>
                            <p class="my-1">{{ $motor_vehicle['color'] }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Usage</span>
                            <p class="my-1">{{ $motor_vehicle['usage'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Owner</h5>
                    </div>
                    <div class="card-body">
                        <div class="row my-2">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Name</span>
                                <p class="my-1">{{ $motor_vehicle['owner']['name'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">TIN</span>
                                <p class="my-1">{{ $motor_vehicle['owner']['tin'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">State/City</span>
                                <p class="my-1">{{ $motor_vehicle['owner']['city'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Address</span>
                                <p class="my-1">{{ $motor_vehicle['owner']['address'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Street</span>
                                <p class="my-1">{{ $motor_vehicle['owner']['street'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Shehia</span>
                                <p class="my-1">{{ $motor_vehicle['owner']['shehia'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Postal Address</span>
                                <p class="my-1">{{ $motor_vehicle['owner']['postal_address'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Office Number</span>
                                <p class="my-1">{{ $motor_vehicle['owner']['office_number'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Email</span>
                                <p class="my-1">{{ $motor_vehicle['owner']['email'] }}</p>
                            </div>
                        </div>

                        <div class="row">

                        </div>

                    </div>

                </div>
            @else
                <div class="card mt-3">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="text-center m-3 text-center h3"><i class="fa fa-search text-danger"></i></div>
                                <h3 class="font-weight-bold text-center m-3 text-danger">{{$message}}</h3>
                            </div>

                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection