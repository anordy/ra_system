@extends('layouts.master')

@section('title', 'Road Inspection Offence')

@section('content')
    @php($taxpayer = $license->drivers_license_owner->taxpayer)
    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
             <h5>Offence</h5>
        </div>
        <div class="card-body">

            <div class="row m-2">
                <div class="col-md-12 form-group">
                    <br>
                    <h5>License Details</h5>
                    <hr/>
                    <div class="row my-2">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Full Name</span>
                            <p class="my-1">{{ "{$taxpayer->first_name} {$taxpayer->middle_name} {$taxpayer->last_name}" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Email Address</span>
                            <p class="my-1">{{ $taxpayer->email }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Mobile</span>
                            <p class="my-1">{{ $taxpayer->mobile }}</p>
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">License Classes</span>
                            <p class="my-1">
                                @foreach($license->drivers_license_classes()->get() as $class)
                                    {{ $class->license_class->name }},
                                @endforeach
                            </p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Issued Date</span>
                            <p class="my-1">{{ $license->issued_date }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Expire Date</span>
                            <p class="my-1">{{ $license->expiry_date }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row m-2">
                <div class="col-md-12 form-group">
                    <br>
                    <h5>Motor Vehicle Details</h5>
                    <hr/>
                    <div class="row my-2">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Make</span>
                            <p class="my-1">{{ $mvr->motor_vehicle->model->make->name}}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Modal</span>
                            <p class="my-1">{{ $mvr->motor_vehicle->model->name}}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Plate Number</span>
                            <p class="my-1">{{ $mvr->plate_number??''}}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Chassis Number</span>
                            <p class="my-1">{{ $mvr->motor_vehicle->chassis_number??''}}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Owner</span>
                            <p class="my-1">{{ $mvr->motor_vehicle->current_owner->taxpayer->fullname()??''}}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Owner TIN</span>
                            <p class="my-1">{{ $mvr->motor_vehicle->current_owner->taxpayer->tin??''}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row m-2">
                <div class="col-md-12">
                    <h5>Offenses</h5>
                    <hr/>
                </div>
                <div class="col-md-6">
                    <ol class="list-group-flush">
                        @foreach ($register->register_offences as $row)
                            <li class="p-1"> {{ $row->offence->name }}</li>
                        @endforeach
                    </ol>
                </div>
                <div class="col-md-6">
                    <span class="font-weight-bold text-uppercase">Date</span>
                    <p class="my-1">{{ $register->created_at->format('d-m-Y')}}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-12 m-3">
                    <h5>Restrictions</h5>
                    <hr/>
                    {{$register->block_type}} <strong>{{!empty($register->block_status)?"($register->block_status)":""}}</strong>
                    <br>
                    <br>
                    @if($register->block_status=='ACTIVE')
                        <div class="d-flex">
                            <a href="{{route('rio.register.remove-restriction',encrypt($register->id))}}" class="justify-content-end d-inline-block">
                                <button  class="btn btn-primary">Remove Restriction</button>
                            </a>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
@endsection