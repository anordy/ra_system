@extends('layouts.master')

@section('title', 'Business Registration Details')

@section('content')
    <div class="card mt-3 border-0 shadow-sm">
        <div class="card-header font-weight-bold text-white {{ $business->verified_at ? 'bg-success' : 'bg-info' }}">
            Business Application Status
        </div>
        <div class="card-body pb-0">
            <div class="row my-2">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $business->name }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3 shadow-sm">
        <div class="card-header font-weight-bold">
            Business Information
        </div>
        <div class="card-body pb-0">
            <div class="row my-2">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Identification No. (TIN)</span>
                    <p class="my-1">{{ $business->tin }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ "{$business->name} {$business->middle_name} {$business->last_name}" }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Reg. No.</span>
                    <p class="my-1">{{ $business->reg_no }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Owner Designation</span>
                    <p class="my-1">{{ $business->owner_designation }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Mobile</span>
                    <p class="my-1">{{ $business->mobile }}</p>
                </div>
                @if($business->alt_mobile)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Alternative Mobile No.</span>
                        <p class="my-1">{{ $business->alt_mobile }}</p>
                    </div>
                @endif
                @if($business->email_address)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Email Address</span>
                        <p class="my-1">{{ $business->email }}</p>
                    </div>
                @endif
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Place of Business</span>
                    <p class="my-1">{{ $business->place_of_business }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Physical Address</span>
                    <p class="my-1">{{ $business->physcal_address }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Date of Commencing Business</span>
                    <p class="my-1">{{ $business->date_of_commencing->toFormattedDateString() }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Estimated Turnover (Next 12 Months) TZS</span>
                    <p class="my-1">{{ $business->post_estimated_turnover }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Estimated Turnover (Last 12 Months) TZS</span>
                    <p class="my-1">{{ $business->pre_estimated_turnover }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Type of Business Activities</span>
                    <p class="my-1">{{ $business->activityType->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Currency</span>
                    <p class="my-1">{{ $business->currency->iso }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Types of Goods or Services Provided</span>
                    <p class="my-1">{{ $business->goods_and_services_types }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Example of Goods or Services Provided</span>
                    <p class="my-1">{{ $business->goods_and_services_example }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Types</span>
                    <p class="my-1">
                        @foreach($business->taxTypes as $type)
                            {{ $type->name }};
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if($location = $business->location)
        <div class="card mt-3">
            <div class="card-header font-weight-bold">
                Business Location
            </div>
            <div class="card-body pb-0">
                <div class="row my-2">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Nature of Premises</span>
                        <p class="my-1">{{ $location->nature_of_possession }}</p>
                    </div>
                    @if($location->owner_name)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Owner's Name</span>
                            <p class="my-1">{{ $location->owner_name }}</p>
                        </div>
                    @endif
                    @if($location->owner_mobile)
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
                        <p class="my-1">{{ $location->place_of_business }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Physical Address</span>
                        <p class="my-1">{{ $location->physcal_address }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">House No.</span>
                        <p class="my-1">{{ $location->house_no }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Latitude</span>
                        <p class="my-1">{{ $location->latitude }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Longitude</span>
                        <p class="my-1">{{ $location->longitude }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($partners = $business->partners)
        <h6 class="my-3">Business Partners</h6>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Reference No.</th>
                <th>Mobile</th>
            </tr>
            </thead>
            <tbody>
            @foreach($partners as $partner)
                <tr class="col-md-4 mb-3">
                    <td class="font-weight-bold text-uppercase">{{ $partner->taxpayer->full_name }}</td>
                    <td class="my-1">{{ $partner->taxpayer->reference_no }}</td>
                    <td class="my-1">{{ $partner->taxpayer->mobile }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    <div class="card mt-3">
        <div class="card-header font-weight-bold">
            Business Responsible Person & Tax Agent
        </div>
        <div class="card-body pb-0">
            <div class="row my-0">
                @if($consultant = $business->consultant)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Consultant Name</span>
                        <p class="my-1">{{ $consultant->taxpayer->full_name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Consultant Ref No.</span>
                        <p class="my-1">{{ $consultant->taxpayer->reference_no }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Consultant Status</span>
                        <p class="my-1 text-success font-weight-bold">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            Approved by Tax Agent
                        </p>
                    </div>
                @else
                    @if($request = $business->consultantRequest)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Consultant Name</span>
                            <p class="my-1">{{ $request->taxpayer->full_name }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Consultant Ref No.</span>
                            <p class="my-1">{{ $request->taxpayer->reference_no }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Consultant Status</span>
                            <p class="my-1 text-danger font-weight-bold">
                                <i class="bi bi-x-circle-fill mr-1"></i>
                                Waiting Approval From Tax Agent
                            </p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    @if($bank = $business->bank)
        <div class="card my-3 ">
            <div class="card-header font-weight-bold">
                Business Bank Account Information
            </div>
            <div class="card-body pb-0">
                <div class="row my-2">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Account No.</span>
                        <p class="my-1">{{ $bank->acc_no }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Account Type</span>
                        <p class="my-1">{{ $bank->accountType->name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Currency</span>
                        <p class="my-1">{{ $bank->currency->iso }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Bank Name</span>
                        <p class="my-1">{{ $bank->bank->name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Branch</span>
                        <p class="my-1">{{ $bank->branch }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection