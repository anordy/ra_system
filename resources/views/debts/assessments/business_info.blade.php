<div class="row m-2 pt-3">
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Business Status</span>
        <p class="my-1">
            @if ($assessment->business->status === \App\Models\BusinessStatus::APPROVED)
                <span class="font-weight-bold text-success">
                    <i class="bi bi-check-circle-fill mr-1"></i>
                    Approved
                </span>
            @elseif($assessment->business->status === \App\Models\BusinessStatus::REJECTED)
                <span class="font-weight-bold text-danger">
                    <i class="bi bi-check-circle-fill mr-1"></i>
                    Rejected
                </span>
            @elseif($assessment->business->status === \App\Models\BusinessStatus::CORRECTION)
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
        <p class="my-1">{{ $assessment->business->name }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Business Category</span>
        <p class="my-1">{{ $assessment->business->category->name }}</p>
    </div>
    @if ($assessment->business->business_type === \App\Models\BusinessType::HOTEL)
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Business Type</span>
            <p class="my-1">Hotel</p>
        </div>
    @endif
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Taxpayer Identification Number (TIN)</span>
        <p class="my-1">{{ $assessment->business->tin }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Business Reg. No.</span>
        <p class="my-1">{{ $assessment->business->reg_no ?? 'N/A' }} </p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Owner Designation</span>
        <p class="my-1">{{ $assessment->business->owner_designation }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Mobile</span>
        <p class="my-1">{{ $assessment->business->mobile }}</p>
    </div>
    @if ($assessment->business->alt_mobile)
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Alternative Mobile No.</span>
            <p class="my-1">{{ $assessment->business->alt_mobile }}</p>
        </div>
    @endif
    @if ($assessment->business->email_address)
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Email Address</span>
            <p class="my-1">{{ $assessment->business->email }}</p>
        </div>
    @endif
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Place of Business</span>
        <p class="my-1">{{ $assessment->business->place_of_business }}</p>
    </div>
  
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Date of Commencing Business</span>
        <p class="my-1">{{ $assessment->business->date_of_commencing }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Estimated Turnover (Next 12 Months) TZS</span>
        <p class="my-1">{{ $assessment->business->post_estimated_turnover }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Estimated Turnover (Last 12 Months) TZS</span>
        <p class="my-1">{{ $assessment->business->pre_estimated_turnover }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Type of Business Activities</span>
        <p class="my-1">{{ $assessment->business->activityType->name }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Currency</span>
        <p class="my-1">{{ $assessment->business->currency->iso }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Types of Goods or Services Provided</span>
        <p class="my-1">{{ $assessment->business->goods_and_services_types }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Example of Goods or Services Provided</span>
        <p class="my-1">{{ $assessment->business->goods_and_services_example }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Tax Types</span>
        <p class="my-1">
            @foreach ($assessment->business->taxTypes as $type)
                {{ $type->name }};
            @endforeach
        </p>
    </div>
</div>