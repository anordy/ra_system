@extends('layouts.master')

@section('title', 'Driver\'s License')

@section('content')
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab"
               aria-controls="home" aria-selected="true">Driver Licence Details</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="approval-tab" data-toggle="tab" href="#approval" role="tab"
               aria-controls="home">License History</a>
        </li>
    </ul>

    <div class="tab-content border bg-white" id="myTabContent">
        <div class="tab-pane p-3 show active" id="all" role="tabpanel" aria-labelledby="all-tab">
            @include('driver-license.includes.license_info', ['application' => $license->application])
        </div>

        <div class="tab-pane fade p-3" id="approval" role="tabpanel" aria-labelledby="approval-tab">
            <livewire:drivers-license.licenses-table :license-number="$license->license_number" />

        </div>

    </div>

@endsection

