@extends('layouts.master')

@section('title', 'Motor Vehicle')

@section('content')

   @if(!empty($motor_vehicle))
       <div class="card mt-3">
           <div class="card-header">
               <h5 class="">Search Result - {{$search_type=='chassis'?'Chassis':'Plate'}} Number: {{$number}}</h5>
               <div class="card-tools">
                   <button class="btn btn-info btn-sm"
                           onclick="Livewire.emit('showModal', 'mvr.chassis-number-internal-search','{{$result_route ?? 'mvr.internal-search'}}')"><i
                               class="fa fa-plus-circle"></i>
                       New Search</button>
                   <button class="btn btn-primary btn-sm"
                           onclick="Livewire.emit('showModal', '{{$action}}',{{$motor_vehicle->id}})"><i
                               class="fa fa-forward"></i>
                       Initiate Request</button>
               </div>
           </div>
       </div>
       @if(!empty($motor_vehicle->current_registration))
           <div class="card mt-3">
               <div class="card-header">
                   <h5>Registration {{!empty($motor_vehicle->current_registration->plate_number)?' - '.$motor_vehicle->current_registration->plate_number:' '}}</h5>
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
                           <p class="my-1">
                               {{ $motor_vehicle->current_registration->plate_number??' - ' }}
                               {{!empty($motor_vehicle->current_registration->current_active_personalized_registration->plate_number)? '/ Personalized: '.$motor_vehicle->current_registration->current_active_personalized_registration->plate_number : ''}}
                           </p>
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
           <div class="card-body">
               <div class="row my-2">
                   <div class="col-md-4 mb-3">
                       <span class="font-weight-bold text-uppercase">Chassis Number</span>
                       <p class="my-1">{{ $motor_vehicle->chassis_number }}</p>
                   </div>
                   <div class="col-md-4 mb-3">
                       <span class="font-weight-bold text-uppercase">Year</span>
                       <p class="my-1">{{ $motor_vehicle->chassis->year ?? '' }}</p>
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
                       <p class="my-1">{{ $motor_vehicle->class->name ?? '' }}</p>
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
                       <p class="my-1"><a href="{{route('mvr.files',encrypt($motor_vehicle->inspection_report_path))}}">Preview</a></p>
                   </div>
               </div>
               <hr />
               <div class="row">
{{--                   <div class="col-md-4 mb-3">--}}
{{--                       <span class="font-weight-bold text-uppercase">Vehicle Status</span>--}}
{{--                       <p class="my-1">{{$motor_vehicle->vehicle_status->name}}</p>--}}
{{--                   </div>--}}
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
                       <p class="my-1">{{ $motor_vehicle->chassis->importer_tin }}</p>
                   </div>
{{--                   <div class="col-md-4 mb-3">--}}
{{--                       <span class="font-weight-bold text-uppercase">Z-Number</span>--}}
{{--                       <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->reference_no }}</p>--}}
{{--                   </div>--}}
                   <div class="col-md-4 mb-3">
                       <span class="font-weight-bold text-uppercase">TIN</span>
                       <p class="my-1">{{ $motor_vehicle->chassis->importer_tin }}</p>
                   </div>
{{--                   <div class="col-md-4 mb-3">--}}
{{--                       <span class="font-weight-bold text-uppercase">State/City</span>--}}
{{--                       <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->location }}</p>--}}
{{--                   </div>--}}
{{--                   <div class="col-md-4 mb-3">--}}
{{--                       <span class="font-weight-bold text-uppercase">Address</span>--}}
{{--                       <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->physical_address }}</p>--}}
{{--                   </div>--}}
{{--                   <div class="col-md-4 mb-3">--}}
{{--                       <span class="font-weight-bold text-uppercase">Street</span>--}}
{{--                       <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->street }}</p>--}}
{{--                   </div>--}}
{{--                   <div class="col-md-4 mb-3">--}}
{{--                       <span class="font-weight-bold text-uppercase">Shehia</span>--}}
{{--                       <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->shehia }}</p>--}}
{{--                   </div>--}}
{{--                   <div class="col-md-4 mb-3">--}}
{{--                       <span class="font-weight-bold text-uppercase">Mobile</span>--}}
{{--                       <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->mobile }}/{{ $motor_vehicle->current_owner->taxpayer->alt_mobile }}</p>--}}
{{--                   </div>--}}
{{--                   <div class="col-md-4 mb-3">--}}
{{--                       <span class="font-weight-bold text-uppercase">Email</span>--}}
{{--                       <p class="my-1">{{ $motor_vehicle->current_owner->taxpayer->email }}</p>--}}
{{--                   </div>--}}
               </div>

           </div>
       </div>

   @else
       <div class="card mt-3">
           <div class="card-header">
               <h5 class="text-uppercase">Search Results - {{$search_type}}: {{$number}}</h5>
               <div class="card-tools">
                   <button class="btn btn-info btn-sm"
                           onclick="Livewire.emit('showModal', 'mvr.chassis-number-internal-search','{{$result_route ?? 'mvr.internal-search'}}')"><i
                               class="fa fa-plus-circle"></i>
                       New Search</button>
               </div>
           </div>
           <div class="card-body">

               <div class="row">
                   <div class="col-md-12 mb-3">
                       <div class="text-center m-3 text-center h3"><i class="fa fa-search text-danger"></i></div>
                       @if(\Route::currentRouteName() === 'mvr.internal-search-dr')
                           <h3 class="font-weight-bold text-center m-3 text-danger">Motor Vehicle with {{$search_type}} {{$number}} is has not been processed for Deregistration</h3>
                       @else
                            <h3 class="font-weight-bold text-center m-3 text-danger">Motor Vehicle with {{$search_type}} {{$number}} is not found</h3>
                       @endif
                   </div>

               </div>
           </div>
       </div>
   @endif

@endsection