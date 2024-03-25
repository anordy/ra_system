@extends('layouts.master')

@section('title', 'Motor Vehicle - De-Registration Request')

@section('content')

    <div class="card mt-3">
        <div class="card-header">
            <h5>De-Registration Request</h5>
            <div class="card-tools">
                @if($request->request_status->name == \App\Models\MvrRequestStatus::STATUS_RC_PENDING_APPROVAL)
                    @can('mvr_approve_de_registration')
                    <a href="{{route('mvr.de-register-requests.approve',encrypt($request->id))}}">
                       <button class="btn btn-info btn-sm">
                           <i class="bi bi-check-circle-fill"></i>Approve
                        </button>
                    </a>

                        <a href="{{route('mvr.de-register-requests.reject',encrypt($request->id))}}">
                            <button class="btn btn-danger btn-sm">
                                <i class="bi bi-check-circle-fill"></i>Reject
                            </button>
                        </a>
                    @endcan
                @elseif($request->request_status->name == \App\Models\MvrRequestStatus::STATUS_RC_INITIATED)
                    @can('mvr_initiate_de_registration')
                        <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'upload-de-registration-inspection-report',{{$request->id}})">
                            <i class="bi bi-check-circle-fill"></i>Submit
                        </button>
                    @endcan
                @elseif($request->request_status->name == \App\Models\MvrRequestStatus::STATUS_RC_ACCEPTED)
                    <a href="{{route('mvr.de-registration-certificate',encrypt($request->mvr_motor_vehicle_id))}}">
                        <button class="btn btn-info btn-sm">
                            <i class="bi bi-printer-fill"></i> Certificate of De-registration
                        </button>
                    </a>
                @endif

            </div>
        </div>
        <div class="card-body">
            @if($request->request_status->name == \App\Models\MvrRequestStatus::STATUS_RC_PENDING_PAYMENT)
                <div class="row my-2">
                    <div class="col-md-12 mb-3">
                        <div class="alert alert-info">
                            <div>Pending Payment for de-registration, current registration type: <strong>'{{$request->motor_vehicle->current_registration->registration_type->name}}'</strong> </div>
                            <br>
                            <div>
                                <div>
                                    De-registration Fee: <strong> {{number_format($request->get_latest_bill()->amount)}} TZS</strong><br>
                                </div>
                                <div>
                                    Control Number: <strong>{!! $request->get_latest_bill()->control_number ?? ' <span class="text-danger">Not available</span>' !!}</strong>
                                </div>
                                @if($request->get_latest_bill()->control_number)
                                    <div>
                                        Control Number Expiry: <strong>{!! $request->get_latest_bill()->expire_date ?? ' <span class="text-danger"></span>' !!}</strong>
                                    </div>
                                @endif
                                <br>
                                @if($request->get_latest_bill()->zan_trx_sts_code != \App\Services\ZanMalipo\ZmResponse::SUCCESS)
                                    <a href="{{route('control-number.retry',['id'=>encrypt($request->get_latest_bill()->id)])}}">
                                        <button class="btn btn-secondary btn-sm btn-rounded">
                                            Request Control Number</button>
                                    </a>
                                @elseif($request->get_latest_bill()->is_waiting_callback())
                                    <div>Refresh after 30 seconds to get control number</div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            @endif
            <div class="row my-2">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Registration Type</span>
                    <p class="my-1">{{ $request->motor_vehicle->current_registration->registration_type->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Reason for De-registration</span>
                    <p class="my-1">{{ $request->de_registration_reason->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase"> Description </span>
                    <p class="my-1">{{ $request->description }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase"> Inspection Report </span>
                    <p class="my-1"><a href="{{ url('storage/'.$request->inspection_report_path) }}">Download/Preview</a></p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Date Received</span>
                    <p class="my-1">{{ $request->date_received??' N/A ' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Request Status</span>
                    <p class="my-1">
                        <span class="badge badge-info">{{$request->request_status->name }}</span>
                    </p>
                </div>

            </div>

        </div>
    </div>

    @if(!empty($motor_vehicle->current_registration))
        <div class="card mt-3">
            <div class="card-header">
                <h5>Current Registration {{!empty($motor_vehicle->current_registration->plate_number)?' - Plate Number: '.$motor_vehicle->current_registration->plate_number:' '}}</h5>
            </div>
            <div class="card-body">
                <div class="row my-2">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Registration Type</span>
                        <p class="my-1">{{ $motor_vehicle->current_registration->registration_type->name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Plate Number Color</span>
                        <p class="my-1">{{ $motor_vehicle->current_registration->registration_type->plate_number_color }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Plate Number Size</span>
                        <p class="my-1">{{ $motor_vehicle->current_registration->plate_size->name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Plate Number</span>
                        <p class="my-1">{{ $motor_vehicle->current_registration->plate_number??' - ' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Plate Number Status</span>
                        <p class="my-1">
                            <span class="badge badge-info">{{ $motor_vehicle->current_registration->plate_number_status->name }}</span>
                        </p>
                    </div>

                </div>

            </div>
        </div>
    @endif

    <!--- Motor Vehicle --->
    <div class="card mt-3">
        <div class="card-header">
            <h5>Motor Vehicle Details - Chassis Number: {{$motor_vehicle->chassis_number}}</h5>
        </div>
        <div class="card-body">
            <div class="row my-2">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Chassis Number</span>
                    <p class="my-1">{{ $motor_vehicle->chassis_number }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Year</span>
                    <p class="my-1">{{ $motor_vehicle->chassis->year }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">imported from</span>
                    <p class="my-1">{{ $motor_vehicle->chassis->imported_from }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Engine capacity</span>
                    <p class="my-1">{{ $motor_vehicle->chassis->engine_cubic_capacity }}</p>
                </div>
{{--                <div class="col-md-4 mb-3">--}}
{{--                    <span class="font-weight-bold text-uppercase">Class</span>--}}
{{--                    <p class="my-1">{{ $motor_vehicle->class->name ?? '' }}</p>--}}
{{--                </div>--}}
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Fuel type</span>
                    <p class="my-1">{{ $motor_vehicle->chassis->fuel_type }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Make</span>
                    <p class="my-1">{{ $motor_vehicle->chassis->make }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Model</Span>
                    <p class="my-1">{{ $motor_vehicle->chassis->model_type }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase"> Custom number</span>
                    <p class="my-1">{{ $motor_vehicle->chassis->tansad_number }}</p>
                </div>
            </div>
            <div class="row">

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Gross weight</span>
                    <p class="my-1">{{ $motor_vehicle->chassis->gross_weight }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Color</span>
                    <p class="my-1">{{ $motor_vehicle->chassis->color }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Inspection Report</span>
                    <p class="my-1"><a href="{{url('storage/'.$motor_vehicle->inspection_report_path)}}">Preview</a></p>
                </div>
            </div>
            <hr />
            <div class="row">
{{--                <div class="col-md-4 mb-3">--}}
{{--                    <span class="font-weight-bold text-uppercase">Vehicle Status</span>--}}
{{--                    <p class="my-1">{{$motor_vehicle->vehicle_status->name}}</p>--}}
{{--                </div>--}}
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Registration Status</span>
                    <p class="my-1"><span class="badge-info badge font-weight-bold">{{$motor_vehicle->registration_status->name}}</span></p>
                </div>
            </div>
        </div>
    </div>

    <!--- Owner --->
    <div class="card mt-3">
        <div class="card-header">
            <h5>Owner</h5>
        </div>
        <div class="card-body">
            <div class="row my-2">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Name</span>
                    <p class="my-1">{{ $motor_vehicle->chassis->importer_name }}</p>
                </div>
{{--                <div class="col-md-4 mb-3">--}}
{{--                    <span class="font-weight-bold text-uppercase">Z-Number</span>--}}
{{--                    <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->reference_no }}</p>--}}
{{--                </div>--}}
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">TIN</span>
                    <p class="my-1">{{ $motor_vehicle->chassis->importer_tin }}</p>
                </div>
{{--                <div class="col-md-4 mb-3">--}}
{{--                    <span class="font-weight-bold text-uppercase">State/City</span>--}}
{{--                    <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->location }}</p>--}}
{{--                </div>--}}
{{--                <div class="col-md-4 mb-3">--}}
{{--                    <span class="font-weight-bold text-uppercase">Address</span>--}}
{{--                    <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->physical_address }}</p>--}}
{{--                </div>--}}
{{--                <div class="col-md-4 mb-3">--}}
{{--                    <span class="font-weight-bold text-uppercase">Street</span>--}}
{{--                    <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->street }}</p>--}}
{{--                </div>--}}
{{--                <div class="col-md-4 mb-3">--}}
{{--                    <span class="font-weight-bold text-uppercase">Shehia</span>--}}
{{--                    <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->shehia }}</p>--}}
{{--                </div>--}}
{{--                <div class="col-md-4 mb-3">--}}
{{--                    <span class="font-weight-bold text-uppercase">Mobile</span>--}}
{{--                    <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->mobile }}/{{ $motor_vehicle->current_owner->taxpayer->alt_mobile }}</p>--}}
{{--                </div>--}}
{{--                <div class="col-md-4 mb-3">--}}
{{--                    <span class="font-weight-bold text-uppercase">Email</span>--}}
{{--                    <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->email }}</p>--}}
{{--                </div>--}}
            </div>

        </div>
    </div>

    <!--- Agent --->
    <div class="card mt-3">
        <div class="card-header">
            <h5>Agent</h5>
        </div>
        <div class="card-body">
            <div class="row my-2">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Name</span>
                    <p class="my-1">{{ $request->agent->taxpayer->fullname() }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">TIN</span>
                    <p class="my-1">{{ $request->agent->taxpayer->tin }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">State/City</span>
                    <p class="my-1">{{ $request->agent->taxpayer->location }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Mobile</span>
                    <p class="my-1">{{ $request->agent->taxpayer->mobile }}/{{ $request->agent->taxpayer->alt_mobile }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">E-mail</span>
                    <p class="my-1">{{ $request->agent->taxpayer->email }}</p>
                </div>
            </div>

        </div>
    </div>

@endsection