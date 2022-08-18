@extends('layouts.master')

@section('title', 'Motor Vehicle - Ownership Transfer')

@section('content')

    <div class="card mt-3">
        <div class="card-header">
            <h5>Ownership Transfer Request</h5>
            <div class="card-tools">
                @if($request->request_status->name == \App\Models\MvrRequestStatus::STATUS_RC_PENDING_APPROVAL)
                    <button class="btn btn-primary   btn-sm"
                            onclick="Livewire.emit('showModal', 'mvr.approve-ownership-transfer','{{$request->id}}')">
                        <i class="fa fa-check"></i> Approve
                    </button>
                    <a href="{{route('mvr.transfer-ownership.reject',encrypt($request->id))}}">
                        <button class="btn btn-danger btn-sm">
                            <i class="fa fa-stop"></i> Approve
                        </button>
                    </a>
                @elseif($request->request_status->name == \App\Models\MvrRequestStatus::STATUS_RC_INITIATED)
                    <button class="btn btn-info btn-sm"
                            onclick="Livewire.emit('showModal', 'mvr.upload-sale-agreement-modal','{{$request->id}}')"><i
                                class="fa fa-upload"></i>
                        Upload Agreement Contract</button>
                @elseif($request->request_status->name == \App\Models\MvrRequestStatus::STATUS_RC_ACCEPTED && \Illuminate\Support\Facades\Gate::has())
                    <a href="{{route('mvr.certificate-of-worth',encrypt($motor_vehicle->id))}}" class="btn btn-info btn-sm text-white"
                       data-bs-toggle="modal" data-bs-target="#confirm-submit-inspection" style="color: #ffffff !important;"><i
                                class="fa fa-print text-white"></i>
                        New Certificate of Registration</a><!--- todo: Missing format for cert fo registration - NCR -->
                @endif

            </div>
        </div>
        <div class="card-body">
            @if($request->request_status->name == \App\Models\MvrRequestStatus::STATUS_RC_PENDING_PAYMENT)
                <div class="row my-2">
                    <div class="col-md-12 mb-3">
                        <div class="alert alert-info">
                            <div>Pending Payment for transfer ownership to: <strong>{{$request->new_owner->fullname()}}</strong> </div>
                            <br>
                            <div>
                                <div>
                                    Transfer Fee: <strong> {{number_format($request->get_latest_bill()->amount)}} TZS</strong><br>
                                </div>
                                <div>
                                    Control Number: <strong>{!! $request->get_latest_bill()->control_number ?? ' <span class="text-danger">Not available</span>' !!}</strong>
                                </div>
                                @if($request->get_latest_bill()->control_number)
                                    <div>
                                        Control Number Expiry: <strong>{!! $request->get_latest_bill()->expiry_date ?? ' <span class="text-danger"></span>' !!}</strong>
                                    </div>
                                @endif
                                <br>
                                @if($request->get_latest_bill()->zan_trx_sts_code != \App\Services\ZanMalipo\ZmResponse::SUCCESS)
                                    <button class="btn btn-secondary btn-sm btn-rounded">
                                        Request Control Number</button>
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
                    <span class="font-weight-bold text-uppercase">Reason for transfer</span>
                    <p class="my-1">{{ $request->ownership_transfer_reason->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase"> Reason Description </span>
                    <p class="my-1">{{ $request->transfer_reason }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">New Owner/Z-number</span>
                    <p class="my-1">{{ $request->new_owner->fullname() }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Transfer Category</span>
                    <p class="my-1">{{ $request->transfer_category->name}}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Market Value</span>
                    <p class="my-1">{{ $request->market_value }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Sale Date</span>
                    <p class="my-1">{{ $request->sale_date }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Delivered Date</span>
                    <p class="my-1">{{ $request->delivered_date }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Date Received</span>
                    <p class="my-1">{{ $request->application_date ??' N/A ' }}</p>
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
                <h5>Current Registration {{!empty($motor_vehicle->current_registration->plate_number)?' - Plate #: '.$motor_vehicle->current_registration->plate_number:' '}}</h5>
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
            <h5>Motor Vehicle Details - Chassis #: {{$motor_vehicle->chassis_number}}</h5>
        </div>
        <div class="card-body">
            <div class="row my-2">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Chassis Number</span>
                    <p class="my-1">{{ $motor_vehicle->chassis_number }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Year</span>
                    <p class="my-1">{{ $motor_vehicle->year_of_manufacture }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">imported from</span>
                    <p class="my-1">{{ $motor_vehicle->imported_from_country->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Engine capacity</span>
                    <p class="my-1">{{ $motor_vehicle->engine_capacity }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Class</span>
                    <p class="my-1">{{ $motor_vehicle->class->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Fuel type</span>
                    <p class="my-1">{{ $motor_vehicle->fuel_type->name }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Make</span>
                    <p class="my-1">{{ $motor_vehicle->model->make->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Model</Span>
                    <p class="my-1">{{ $motor_vehicle->model->name}}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase"> Custom number</span>
                    <p class="my-1">{{ $motor_vehicle->custom_number }}</p>
                </div>
            </div>
            <div class="row">

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Gross weight</span>
                    <p class="my-1">{{ $motor_vehicle->gross_weight }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Color</span>
                    <p class="my-1">{{ $motor_vehicle->color->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Inspection Report</span>
                    <p class="my-1"><a href="{{url('storage/'.$motor_vehicle->inspection_report_path)}}">Preview</a></p>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Vehicle Status</span>
                    <p class="my-1">{{$motor_vehicle->vehicle_status->name}}</p>
                </div>
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
                    <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->fullname() }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Z-Number</span>
                    <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->reference_no }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">TIN</span>
                    <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->reference_no }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">State/City</span>
                    <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->location }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Address</span>
                    <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->physical_address }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Street</span>
                    <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->street }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Shehia</span>
                    <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->shehia }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Mobile</span>
                    <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->mobile }}/{{ $motor_vehicle->current_owner->taxpayer->alt_mobile }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Email</span>
                    <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->email }}</p>
                </div>
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
                    <p class="my-1">{{ $request->agent->fullname() }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Z-Number</span>
                    <p class="my-1">{{ $request->agent->reference_no }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">TIN</span>
                    <p class="my-1">{{ $request->agent->reference_no }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">State/City</span>
                    <p class="my-1">{{ $request->agent->location }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Mobile</span>
                    <p class="my-1">{{ $request->agent->mobile }}/{{ $request->agent->alt_mobile }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Email</span>
                    <p class="my-1">{{ $request->agent->email }}</p>
                </div>
            </div>

        </div>
    </div>

@endsection