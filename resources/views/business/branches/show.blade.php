@extends('layouts.master')

@section('title')
    Business Branch
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            {{ $location->name }} Business Branch
        </div>
        <div class="card-body">
            {{-- @include('business.registrations.includes.business_info') --}}

            <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Branch Information</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="approval-tab" data-toggle="tab" href="#approval" role="tab"
                        aria-controls="approval" aria-selected="false">Approval History</a>
                </li>
            </ul>

            <div class="tab-content bg-white border shadow-sm" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row m-2 pt-3">
                        <div class="card">
                            <div class="card-header">New Branch Details</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Branch Name</span>
                                        <p class="my-1">{{ $location->name }}</p>
                                    </div>
                                    @if ($location->zin)
                                        <div class="col-md-4 mb-3">
                                            <span class="font-weight-bold text-uppercase">ZIN</span>
                                            <p class="my-1">{{ $location->zin }}</p>
                                        </div>
                                    @endif
                                    @if ($location->taxRegion)
                                        <div class="col-md-4 mb-3">
                                            <span class="font-weight-bold text-uppercase">Tax Region</span>
                                            <p class="my-1">{{ $location->taxRegion->name }}</p>
                                        </div>
                                    @endif
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Nature of Premises</span>
                                        <p class="my-1">{{ $location->nature_of_possession }}</p>
                                    </div>
                                    @if ($location->owner_name)
                                        <div class="col-md-4 mb-3">
                                            <span class="font-weight-bold text-uppercase">Owner's Name</span>
                                            <p class="my-1">{{ $location->owner_name }}</p>
                                        </div>
                                    @endif
                                    @if ($location->owner_mobile)
                                        <div class="col-md-4 mb-3">
                                            <span class="font-weight-bold text-uppercase">Owner's Mobile</span>
                                            <p class="my-1">{{ $location->owner_mobile }}</p>
                                        </div>
                                    @endif
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Electric Metre No.</span>
                                        <p class="my-1">{{ $location->meter_no }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Region.</span>
                                        <p class="my-1">{{ $location->region->name }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">District</span>
                                        <p class="my-1">{{ $location->district->name }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Ward</span>
                                        <p class="my-1">{{ $location->ward->name }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Street</span>
                                        <p class="my-1">{{ $location->street }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Physical Address</span>
                                        <p class="my-1">{{ $location->physical_address }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Date of Commencing</span>
                                        <p class="my-1">{{ $location->date_of_commencing->toFormattedDateString() }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Pre Estimated Turnover</span>
                                        <p class="my-1">{{ number_format($location->pre_estimated_turnover ?? 0, 2) }}
                                        </p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Post Estimated Turnover</span>
                                        <p class="my-1">{{ number_format($location->post_estimated_turnover ?? 0, 2) }}
                                        </p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">House No.</span>
                                        <p class="my-1">{{ $location->house_no }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Contact Name</span>
                                        <p class="my-1">{{ $location->contact_name }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">PO Box</span>
                                        <p class="my-1">{{ $location->po_box ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Fax No</span>
                                        <p class="my-1">{{ $location->fax ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Mobile</span>
                                        <p class="my-1">{{ $location->mobile }}</p>
                                    </div>
                                    @if ($location->alt_mobile)
                                        <div class="col-md-4 mb-3">
                                            <span class="font-weight-bold text-uppercase">Alt Mobile</span>
                                            <p class="my-1">{{ $location->alt_mobile }}</p>
                                        </div>
                                    @endif
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Latitude</span>
                                        <p class="my-1">{{ $location->latitude }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Longitude</span>
                                        <p class="my-1">{{ $location->longitude }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Branch Status</span>
                                        <p class="my-1 font-weight-bold">
                                            @if ($location->status === \App\Models\BranchStatus::APPROVED)
                                                <span class="font-weight-bold text-success">
                                                    <i class="bi bi-check-circle-fill mr-1"></i>
                                                    Approved
                                                </span>
                                            @elseif($location->status === \App\Models\BranchStatus::CORRECTION)
                                                <span class="font-weight-bold text-warning">
                                                    <i class="bi bi-pen-fill mr-1"></i>
                                                    Requires Correction
                                                </span>
                                            @elseif($location->status === \App\Models\BranchStatus::REJECTED)
                                                <span class="font-weight-bold text-danger">
                                                    <i class="bi bi-check-circle-fill mr-1"></i>
                                                    Rejected
                                                </span>
                                            @else
                                                <span class="font-weight-bold text-info">
                                                    <i class="bi bi-clock-history mr-1"></i>
                                                    Waiting Approval
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-12 mt-1 d-flex justify-content-end mb-4">
                                        @if ($location->status === \App\Models\BusinessStatus::APPROVED)
                                            <div>
                                                @foreach ($location->business->taxTypes as $type)
                                                    <a target="_blank"
                                                        href="{{ route('business.certificate', ['location' => encrypt($location->id), 'type' => encrypt($type->id)]) }}"
                                                        class="btn btn-success btn-sm mt-1 text-white">
                                                        <i class="bi bi-patch-check"></i>
                                                        {{ $type->name }} Certificate
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($hotel = $location->hotel)
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="text-uppercase">Hotel Details</h6>
                                    <hr>
                                    <div class="row">
                                        @if ($hotel->company_name)
                                        <div class="col-md-4 mb-3">
                                            <span class="font-weight-bold text-uppercase">Company Name</span>
                                            <p class="my-1">{{ $hotel->company_name }}</p>
                                        </div>
                                        @endif
                                        @if ($hotel->management_company)
                                        <div class="col-md-4 mb-3">
                                            <span class="font-weight-bold text-uppercase">Management Company</span>
                                            <p class="my-1">{{ $hotel->management_company }}</p>
                                        </div>  
                                        @endif
                                        <div class="col-md-4 mb-3">
                                            <span class="font-weight-bold text-uppercase">Hotel Location</span>
                                            <p class="my-1">{{ $hotel->hotel_location }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <span class="font-weight-bold text-uppercase">Number of Rooms</span>
                                            <p class="my-1">{{ $hotel->number_of_rooms }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <span class="font-weight-bold text-uppercase">Number of Single Rooms</span>
                                            <p class="my-1">{{ $hotel->number_of_single_rooms }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <span class="font-weight-bold text-uppercase">Number of Double Rooms</span>
                                            <p class="my-1">{{ $hotel->number_of_double_rooms }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <span class="font-weight-bold text-uppercase">Number of Other Rooms</span>
                                            <p class="my-1">{{ $hotel->number_of_other_rooms }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <span class="font-weight-bold text-uppercase">Hotel Capacity</span>
                                            <p class="my-1">{{ $hotel->hotel_capacity }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <span class="font-weight-bold text-uppercase">Average Charging Rate (Per night
                                                per
                                                person for bed
                                                and breakfast)</span>
                                            <p class="my-1">{{ number_format($hotel->average_rate ?? 0, 2) }}</p>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <span class="font-weight-bold text-uppercase">Other Services</span>
                                            <p class="my-1">{{ $hotel->other_services }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>


                </div>
                <div class="tab-pane fade m-2" id="approval" role="tabpanel" aria-labelledby="approval-tab">
                    <livewire:approval.approval-history-table modelName='App\Models\BusinessLocation'
                        modelId="{{ encrypt($location->id) }}" />
                </div>
            </div>

            <div class="mt-4">
                <livewire:approval.branches-approval-processing modelName='App\Models\BusinessLocation'
                    modelId="{{ encrypt($location->id) }}" />
            </div>


        </div>
    </div>
@endsection
