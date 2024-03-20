@extends('layouts.master')

@section('title', $business->name . ' Ledger')

@section('content')
    <div class="card-body mt-0 p-2">
        <ul class="nav nav-tabs shadow-sm mb-0" id="taxpayerLedger" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="taxclearance-info-tab" data-toggle="tab" href="#taxclearance-info"
                    role="tab" aria-controls="taxclearance-info" aria-selected="true">Tax Clearence Information </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="return-infos-tab" data-toggle="tab" href="#return-infos" role="tab"
                    aria-controls="return-infos" aria-selected="false">Return Information</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="debt-infos-tab" data-toggle="tab" href="#debt-infos" role="tab"
                    aria-controls="debt-infos" aria-selected="false">Unpaid Debts Information</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="paid-debt-infos-tab" data-toggle="tab" href="#paid-debt-infos" role="tab"
                    aria-controls="paid-debt-infos" aria-selected="false">Paid Debts Information</a>
            </li>
        </ul>

        <div class="tab-content bg-white border shadow-sm" id="taxpayerLedger">
            <div class="tab-pane fade show active" id="taxclearance-info" role="tabpanel"
                aria-labelledby="taxclearance-info-tab">

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
                        <p class="my-1">{{ $business->name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Category</span>
                        <p class="my-1">{{ $business->category->name }}</p>
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
                        @if (isset($verified))
                            @if ($verified == 'verified')
                                <span class="font-weight-light text-success">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                    TIN Number Verified
                                </span>
                            @else
                                <span class="font-weight-light text-danger">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                    {{ $verified }}
                                </span>
                            @endif
                        @endif
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Reg. No.</span>
                        <p class="my-1">{{ $business->reg_no ?? 'N/A' }} </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Owner Designation</span>
                        <p class="my-1">{{ $business->owner_designation }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Mobile</span>
                        <p class="my-1">{{ $business->mobile }}
                            {{ $business->alt_mobile ? '/ ' . $business->alt_mobile : '' }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Mobile</span>
                        <p class="my-1">{{ $business->email }}</p>
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
                            <span class="font-weight-bold text-uppercase">LTO Business</span>
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
                </div>

            </div>
            <div class="tab-pane fade show" id="return-infos" role="tabpanel" aria-labelledby="return-infos-tab">

                <div class="card rounded-0">
                    <div class="card-header font-weight-bold text-uppercase bg-white">
                        Business Location Tax Returns
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs shadow-sm mb-0">
                            @foreach ($business->locations as $location)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                        id="{{ strtolower(str_replace(' ', '-', $location->name)) }}-tab"
                                        data-toggle="tab"
                                        href="#{{ strtolower(str_replace(' ', '-', $location->name)) }}"
                                        aria-controls="{{ strtolower(str_replace(' ', '-', $location->name)) }}"
                                        role="tab" aria-selected="true">
                                        {{ $location->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content bg-white border shadow-sm" id="myTabContent">
                            @foreach ($business->locations as $location)
                                <div class="tab-pane fade p-3 {{ $loop->first ? 'show active' : '' }}"
                                    id="{{ strtolower(str_replace(' ', '-', $location->name)) }}" role="tabpanel"
                                    aria-labelledby="{{ strtolower(str_replace(' ', '-', $location->name)) }}-tab">
                                    <livewire:business.location-returns-summary locationId="{{ encrypt($location->id) }}" />
                                    <livewire:business.location-returns-table location_id="{{ $location->id }}" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
            <div class="tab-pane fade show" id="debt-infos" role="tabpanel" aria-labelledby="debt-infos-tab">
                @foreach ($business->locations as $location)
                    <div id="accordion">
                        <div class="card">
                            <button class="btn collapsed" data-toggle="collapse"
                                data-target="#unpaidCollapseLocation-{{ $location->id }}" aria-expanded="false"
                                aria-controls="unpaidCollapseLocation-{{ $location->id }}">
                                <div class="card-header" id="headingTwo">
                                    <h5 class="mb-0">
                                        {{ $location->name }}
                                        @if ($location->is_headquarter)
                                            <span>
                                                (Headquater)
                                            </span>
                                        @endif
                                        <span class="ml-2">
                                            <i class="bi bi-chevron-double-down"></i>
                                        </span>
                                    </h5>
                                </div>
                            </button>

                            <div id="unpaidCollapseLocation-{{ $location->id }}" class="collapse" aria-labelledby="headingTwo"
                                data-parent="#accordion">
                                <div class="card-body">
                                    @include('finance.includes.unpaid-debt-info', [
                                        'location_id' => $location->id,
                                        'tax_return_debts' => $unpaidBusinessTaxReturnDebts[$location->id] ?? [],
                                        'land_lease_debts' => $unpaidBusinessLandLeaseDebts[$location->id] ?? [],
                                        'investigationDebts' => $unpaidBusinessInvestigationDebts[$location->id] ?? [],
                                        'auditDebts' => $unpaidBusinessAuditDebts[$location->id] ?? [],
                                        'verificateionDebts' => $unpaidBusinessVerificateionDebts[$location->id] ?? [],
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="tab-pane fade show" id="paid-debt-infos" role="tabpanel" aria-labelledby="paid-debt-infos-tab">
                @foreach ($business->locations as $location)
                    <div id="accordion">
                        <div class="card">
                            <button class="btn collapsed" data-toggle="collapse"
                                data-target="#paidCollapseLocation-{{ $location->id }}" aria-expanded="false"
                                aria-controls="paidCollapseLocation-{{ $location->id }}">
                                <div class="card-header" id="headingTwo">
                                    <h5 class="mb-0">
                                        {{ $location->name }}
                                        @if ($location->is_headquarter)
                                            <span>
                                                (Headquater)
                                            </span>
                                        @endif
                                        <span class="ml-2">
                                            <i class="bi bi-chevron-double-down"></i>
                                        </span>
                                    </h5>
                                </div>
                            </button>

                            <div id="paidCollapseLocation-{{ $location->id }}" class="collapse" aria-labelledby="headingTwo"
                                data-parent="#accordion">
                                <div class="card-body">
                                    @include('finance.includes.paid-debt-info', [
                                        'location_id' => $location->id,
                                        'tax_return_debts' => $paidBusinessTaxReturnDebts[$location->id] ?? [],
                                        'land_lease_debts' => $paidBusinessLandLeaseDebts[$location->id] ?? [],
                                        'investigationDebts' => $paidBusinessInvestigationDebts[$location->id] ?? [],
                                        'auditDebts' => $paidBusinessAuditDebts[$location->id] ?? [],
                                        'verificateionDebts' => $paidBusinessVerificateionDebts[$location->id] ?? [],
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
