@extends('layouts.master')

@section('title', 'Motor Vehicle')

@section('content')

    @if(!empty($motor_vehicle->current_registration))
        <div class="card mt-3">
            <div class="card-header">
                <h5>Registration {{!empty($motor_vehicle->current_registration->plate_number)?' - Plate Number: '.$motor_vehicle->current_registration->plate_number:' '}}</h5>
                <div class="card-tools">
                    @if($motor_vehicle->registration_status->name == \App\Models\MvrRegistrationStatus::STATUS_REGISTERED)
                    <a href="{{route('mvr.certificate-of-registration',encrypt($motor_vehicle->id))}}" class="btn btn-info btn-sm text-white"
                       data-bs-toggle="modal" data-bs-target="#confirm-submit-inspection"><i
                                class="fa fa-print text-white"></i>
                        Certificate of Registration</a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($motor_vehicle->registration_status->name == \App\Models\MvrRegistrationStatus::STATUS_PENDING_PAYMENT)
                    <div class="row my-2">
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-info">
                                <div>Pending Payment for <strong>'{{$motor_vehicle->current_registration->registration_type->name}}'</strong> motor vehicle registration type </div>
                                <br>
                                <div>
                                    <div>
                                        Registration Fee: <strong> {{number_format($motor_vehicle->current_registration->get_latest_bill()->amount ?? 0)}} TZS</strong><br>
                                    </div>
                                    <div>
                                        Control Number: <strong>{!! $motor_vehicle->current_registration->get_latest_bill()->control_number ?? ' <span class="text-danger">Not available</span>' !!}</strong>
                                    </div>
                                    @if($motor_vehicle->current_registration->get_latest_bill()->control_number ?? null)
                                        <div>
                                            Control Number Expiry: <strong>{!! $motor_vehicle->current_registration->get_latest_bill()->expire_date ?? ' <span class="text-danger"></span>' !!}</strong>
                                        </div>
                                    @endif
                                    <br>
                                    @if($motor_vehicle->current_registration->get_latest_bill()->zan_trx_sts_code ?? null != \App\Services\ZanMalipo\ZmResponse::SUCCESS)
                                        <a href="{{route('control-number.retry',['id'=>encrypt($motor_vehicle->current_registration->get_latest_bill()->id)])}}">
                                            <button class="btn btn-secondary btn-sm btn-rounded">
                                                Request Control Number</button>
                                        </a>
                                    @elseif($motor_vehicle->current_registration->get_latest_bill()->is_waiting_callback())
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
                    @if(!empty($motor_vehicle->current_registration->current_personalized_registration))
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Personalize plate Number</span>
                            <p class="my-1">{{ $motor_vehicle->current_registration->current_personalized_registration->plate_number??' - ' }}</p>
                        </div>
                    @endif
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Plate Number Status</span>
                        <p class="my-1">
                            <span class="badge badge-info">{{ $motor_vehicle->current_registration->plate_number_status->name }}</span>
                        </p>
                    </div>

                    @if(!empty($motor_vehicle->current_registration->registration_date))
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Registration Date</span>
                            <p class="my-1">{{ $motor_vehicle->current_registration->registration_date??' - ' }}</p>
                        </div>
                    @endif

                    @if(!empty($motor_vehicle->current_registration->plate_number_collection))
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Plate Number Collection Date</span>
                            <p class="my-1">{{ $motor_vehicle->current_registration->plate_number_collection->collection_date->format('Y-m-d')??' - ' }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Plate Number Collector Name</span>
                            <p class="my-1">{{ $motor_vehicle->current_registration->plate_number_collection->collector_name??' - ' }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Plate Number Collector Phone</span>
                            <p class="my-1">{{ $motor_vehicle->current_registration->plate_number_collection->collector_phone??' - ' }}</p>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    @endif

    <!--- Motor Vehicle --->
    <div class="card mt-3">
        <div class="card-header">
            <h5>Motor Vehicle Details - Chassis Number: {{$motor_vehicle->chassis_number}}</h5>
            <div class="card-tools">
                @if($motor_vehicle->registration_status->name == \App\Models\MvrRegistrationStatus::STATUS_INSPECTION)
                    <a href="{{route('mvr.certificate-of-worth',encrypt($motor_vehicle->id))}}" class="btn btn-info btn-sm text-white"
                       data-bs-toggle="modal" data-bs-target="#confirm-submit-inspection"><i
                                class="fa fa-print text-white"></i>
                        Certificate of Worth</a>
                    @can('mvr_initiate_registration')
                        <a href="{{route('mvr.submit-inspection',encrypt($motor_vehicle->id))}}" class="btn btn-info btn-sm text-white"
                           data-bs-toggle="modal" data-bs-target="#confirm-submit-inspection"><i
                                    class="fa fa-upload text-white"></i>
                            Submit</a>
                    @endcan
                @elseif($motor_vehicle->registration_status->name == \App\Models\MvrRegistrationStatus::STATUS_REVENUE_OFFICER_APPROVAL)
                    @can('mvr_approve_registration')
                        <button class="btn btn-info btn-sm"
                                onclick="Livewire.emit('showModal', 'mvr.approve-registration',{{$motor_vehicle->id}})"><i
                                    class="fa fa-check"></i>
                            Approve</button>
                    @endcan
                    <a href="{{route('mvr.certificate-of-worth',encrypt($motor_vehicle->id))}}" class="btn btn-info btn-sm text-white"
                       data-bs-toggle="modal" data-bs-target="#confirm-submit-inspection"><i
                                class="fa fa-print text-white"></i>
                        Certificate of Worth</a>
                @endif
            </div>
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
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Class</span>
                    <p class="my-1">{{ $motor_vehicle->class->name ?? 'N/A' }}</p>
                </div>
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
                    <p class="my-1">{{ $motor_vehicle->chassis->model_type}}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase"> Custom number</span>
                    <p class="my-1">{{ $motor_vehicle->chassis->tansad_number }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Usage Type</span>
                    <p class="my-1">{{ $motor_vehicle->chassis->usage_type }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Vehicle Category</Span>
                    <p class="my-1">{{ $motor_vehicle->chassis->vehicle_category }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Purchase Day</Span>
                    <p class="my-1">{{ $motor_vehicle->chassis->purchase_day }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Passenger Capacity</span>
                    <p class="my-1">{{ $motor_vehicle->chassis->passenger_capacity }}</p>
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
                    <p class="my-1"><a href="{{route('mvr.files',encrypt($motor_vehicle->inspection_report_path))}}">Preview</a></p>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Vehicle Status</span>
                    <p class="my-1">{{$motor_vehicle->chassis->status}}</p>
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
                    <span class="font-weight-bold text-uppercase">Importer Name</span>
                    <p class="my-1">{{ $motor_vehicle->chassis->importer_name}}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Importer TIN</span>
                    <p class="my-1">{{ $motor_vehicle->chassis->importer_tin }}</p>
                </div>
                {{-- <div class="col-md-4 mb-3">
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
                </div> --}}
            </div>

        </div>
    </div>

    {{-- <!--- Agent --->
    <div class="card mt-3">
        <div class="card-header">
            <h5>Agent</h5>
        </div>
        <div class="card-body">
            <div class="row my-2">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Name</span>
                    <p class="my-1">{{ $motor_vehicle->agent->taxpayer->fullname() }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">TIN</span>
                    <p class="my-1">{{ $motor_vehicle->agent->taxpayer->reference_no }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">State/City</span>
                    <p class="my-1">{{ $motor_vehicle->agent->taxpayer->location }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Mobile</span>
                    <p class="my-1">{{ $motor_vehicle->agent->taxpayer->mobile }}/{{ $motor_vehicle->agent->taxpayer->alt_mobile }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Email</span>
                    <p class="my-1">{{ $motor_vehicle->agent->taxpayer->email }}</p>
                </div>
            </div>

        </div>
    </div> --}}

@endsection