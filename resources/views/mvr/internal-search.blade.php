@extends('layouts.master')

@section('title', 'Motor Vehicle')

@section('content')

   @if(!empty($motor_vehicle))
       <div class="card mt-3">
           <div class="card-header">
               <h5 class="">Search Result - {{$search_type=='chassis'?'Chassis':'Plate'}} #: {{$number}}</h5>
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

   @else
       <div class="card mt-3">
           <div class="card-header">
               <h5 class="text-uppercase">Search Results - {{$search_type}}: {{$number}}</h5>
               <div class="card-tools">
                   <button class="btn btn-info btn-sm"
                           onclick="Livewire.emit('showModal', 'mvr.chassis-number-internal-search','mvr.internal-search')"><i
                               class="fa fa-plus-circle"></i>
                       New Search</button>
               </div>
           </div>
           <div class="card-body">

               <div class="row">
                   <div class="col-md-12 mb-3">
                       <div class="text-center m-3 text-center h3"><i class="fa fa-search text-danger"></i></div>
                       <h3 class="font-weight-bold text-center m-3 text-danger">Motor Vehicle with {{$search_type}} {{$number}} is not found</h3>
                   </div>

               </div>
           </div>
       </div>
   @endif

@endsection