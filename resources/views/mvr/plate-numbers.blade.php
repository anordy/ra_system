@extends('layouts.master')

@section('title', 'Plate Numbers Printing')

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <div class="card-header">
                <h5>Plate Numbers</h5>
                <div class="card-tools">
                </div>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="to-print-link" data-toggle="tab" href="#to-print" role="tab"
                       aria-controls="home" aria-selected="true">To Print</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="printed-link" data-toggle="tab" href="#printed" role="tab"
                       aria-controls="profile" aria-selected="false">Printed</a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="received-link" data-toggle="tab" href="#received" role="tab"
                       aria-controls="profile" aria-selected="false">Received</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane p-2 show active" id="to-print" role="tabpanel" aria-labelledby="to-print-tab">
                    <div class=" disp-Info text-center">
                        To Print
                    </div>
                    <livewire:mvr.plate-numbers-table :plate_number_status="App\Models\MvrPlateNumberStatus::STATUS_GENERATED"/>

                </div>
                <div class="tab-pane p-2" id="printed" role="tabpanel" aria-labelledby="printed-tab">
                    <div class="disp-Info text-center">
                        Printed
                    </div>

                    <livewire:mvr.plate-numbers-table :plate_number_status="App\Models\MvrPlateNumberStatus::STATUS_PRINTED"/>

                </div>

                <div class="tab-pane p-2" id="received" role="tabpanel" aria-labelledby="received-tab">
                    <div class="disp-Info text-center">
                        Received
                    </div>

                    <livewire:mvr.plate-numbers-table :plate_number_status="App\Models\MvrPlateNumberStatus::STATUS_RECEIVED"/>

                </div>

            </div>
        </div>
    </div>
@endsection

