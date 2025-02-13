@extends('layouts.master')

@section('title', 'Business Registration Updates Details')

@section('content')
    <ul class="nav nav-tabs shadow-sm" id="myTab" style="margin-bottom: 0;">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
               aria-selected="true">Business Information Overview</a>
        </li>
    </ul>

    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $business->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">ZTN Number</span>
                    <p class="my-1">{{ $business->ztn_location_number }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">VRN Number</span>
                    <p class="my-1">{{ $business->vrn }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Country</span>
                    <p class="my-1">{{ $business->country->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Updated Date</span>
                    <p class="my-1">{{ $updates->created_at ? \Carbon\Carbon::create($business->created_at)->format('d M, Y') : 'N/A' }}</p>
                </div>

            </div>

            @include('non-tax-resident.updates.includes.information-comparison')
        </div>
    </div>

@endsection