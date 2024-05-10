@extends('layouts.master')

@section('title', 'Plate Numbers Printing')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            Plate Numbers
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                @can('print_plate_number')
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="to-print-link" data-toggle="tab" href="#to-print" role="tab"
                       aria-controls="home" aria-selected="true">To Print</a>
                </li>
                @endcan
                @can('receive_plate_number')
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="printed-link" data-toggle="tab" href="#printed" role="tab"
                           aria-controls="profile" aria-selected="false">Printed</a>
                    </li>
                 @endcan
                 @can('receive_plate_number')
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="received-link" data-toggle="tab" href="#received" role="tab"
                           aria-controls="profile" aria-selected="false">Received</a>
                    </li>
                 @endcan
            </ul>

            <div class="tab-content" id="myTabContent">
                @can('print_plate_number')
                <div class="tab-pane p-2 show active" id="to-print" role="tabpanel" aria-labelledby="to-print-tab">
                    <livewire:mvr.plate-numbers-table :plate_number_status="App\Models\MvrPlateNumberStatus::STATUS_GENERATED"/>
                </div>
                @endcan
                @can('receive_plate_number')
                    <div class="tab-pane p-2" id="printed" role="tabpanel" aria-labelledby="printed-tab">
                        <livewire:mvr.plate-numbers-table :plate_number_status="App\Models\MvrPlateNumberStatus::STATUS_PRINTED"/>
                    </div>
                @endcan
                @can('receive_plate_number')
                <div class="tab-pane p-2" id="received" role="tabpanel" aria-labelledby="received-tab">
                    <livewire:mvr.plate-numbers-table :plate_number_status="App\Models\MvrPlateNumberStatus::STATUS_RECEIVED"/>
                </div>
                @endcan

            </div>
        </div>
    </div>
@endsection

