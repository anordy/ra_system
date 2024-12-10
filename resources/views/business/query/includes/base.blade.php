<div class="pt-3 px-2">
    <ul class="nav nav-tabs shadow-sm mb-0">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
               aria-selected="true">Business Information</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="tax-type-tab" data-toggle="tab" href="#tax-type" role="tab"
               aria-controls="tax-type" aria-selected="false">Tax Types</a>
        </li>
    </ul>

    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Status</span>
                    <p class="my-1">
                        @if ($business->status === \App\Models\BusinessStatus::APPROVED)
                            <span class="font-weight-bold text-success">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Approved
                            </span>
                        @elseif($business->status === \App\Models\BusinessStatus::REJECTED)
                            <span class="font-weight-bold text-danger">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Rejected
                            </span>
                        @elseif($business->status === \App\Models\BusinessStatus::CORRECTION)
                            <span class="font-weight-bold text-warning">
                                <i class="bi bi-pen-fill mr-1"></i>
                                Requires Correction
                            </span>
                        @else
                            <span class="font-weight-bold text-info">
                                <i class="bi bi-clock-history mr-1"></i>
                                Waiting Approval
                            </span>
                        @endif
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $business->name ?? '' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Category</span>
                    <p class="my-1">{{ $business->category->name ?? '' }}</p>
                </div>
                @if ($business->business_type === \App\Models\BusinessType::HOTEL)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Type</span>
                        <p class="my-1">Hotel</p>
                    </div>
                @endif
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Taxpayer Identification Number (TIN)</span>
                    <p class="my-1">{{ $business->tin }}</p>
                </div>
                @if ($business->previous_zno)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Previous ZNO</span>
                        <p class="my-1">{{ $business->previous_zno }}</p>
                    </div>
                @endif
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Reg. No.</span>
                    <p class="my-1">{{ $business->reg_no ?? 'N/A' }}</p>
                </div>
                @if($business->taxpayer_name)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Taxpayer Name</span>
                        <p class="my-1">{{ $business->taxpayer_name }}</p>
                    </div>
                @endif
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Owner Designation</span>
                    <p class="my-1">{{ $business->owner_designation }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Mobile</span>
                    <p class="my-1">{{ $business->mobile }}</p>
                </div>
                @if ($business->alt_mobile)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Alternative Mobile No.</span>
                        <p class="my-1">{{ $business->alt_mobile }}</p>
                    </div>
                @endif
                @if ($business->email_address)
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
                    <span class="font-weight-bold text-uppercase">Type of Business Activities</span>
                    <p class="my-1">{{ $business->activityType->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Type of Business</span>
                    <p class="my-1">{{ $business->business_type }}</p>
                </div>
                @if ($business->is_business_lto)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">LTD Business</span>
                        <p class="my-1">Yes</p>
                    </div>
                @endif
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

                @if ($business->isici)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">ISIC I</span>
                        <p class="my-1">{{ $business->isici->description }}</p>
                    </div>
                @endif
                @if ($business->isicii)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">ISIC II</span>
                        <p class="my-1">{{ $business->isicii->description }}</p>
                    </div>
                @endif
                @if ($business->isiciii)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">ISIC III</span>
                        <p class="my-1">{{ $business->isiciii->description }}</p>
                    </div>
                @endif
                @if ($business->isiciv)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">ISIC IV</span>
                        <p class="my-1">{{ $business->isiciv->description }}</p>
                    </div>
                @endif
                @if ($business->tax_region_id)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Tax Region</span>
                        <p class="my-1">{{ $business->taxRegion->name }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="tab-pane fade" id="tax-type" role="tabpanel" aria-labelledby="tax-type-tab">
            <div class="col-md-12 mt-3">
                <h6 class="mb-0 font-weight-bold">Assigned Tax Types</h6>
                <hr class="mt-2 mb-3"/>
            </div>
            <div class="row m-2">
                @include('business.query.includes.tax_type')
            </div>
        </div>

    </div>
</div>
