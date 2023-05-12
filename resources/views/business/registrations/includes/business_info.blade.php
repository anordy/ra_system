<div class="pt-3 px-2">
    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                aria-selected="true">Business Information</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="location-tab" data-toggle="tab" href="#location" role="tab"
                aria-controls="location" aria-selected="false">Location</a>
        </li>
        @if ($business->partners->count())
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="partners-tab" data-toggle="tab" href="#partners" role="tab"
                    aria-controls="partners" aria-selected="false">Partners</a>
            </li>
        @endif
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                aria-controls="contact" aria-selected="false">Responsible Person</a>
        </li>
        @if (count($business->banks))
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank" role="tab" aria-controls="bank"
                    aria-selected="false">
                    Bank Accounts
                </a>
            </li>
        @endif
        @if ($business->hotel)
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="hotel-tab" data-toggle="tab" href="#hotel" role="tab" aria-controls="hotel"
                    aria-selected="false">Hotel Information</a>
            </li>
        @endif
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="business-attachment-tab" data-toggle="tab" href="#business-attachment"
                role="tab" aria-controls="business-attachment" aria-selected="false">Business Attachments</a>
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
                    <span class="font-weight-bold text-uppercase">Tax Identification No. (TIN)</span>
                    <p class="my-1">{{ $business->tin }}</p>

                    {{-- Start Will be implementated after TRA intergration --}}
                    {{-- @if (isset($verified))
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
                    @else
                        <a href="{{ route('verification.tin', encrypt($business->id)) }}">
                            <button class="btn btn-info">Verify TIN Number</button>
                        </a>
                    @endif --}}
                    {{-- End Will be implementated after TRA intergration --}}

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
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Types</span>
                    <p class="my-1">
                        @foreach ($business->taxTypes as $type)
                            {{ $type->name }};
                        @endforeach
                    </p>
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

                @if (
                    $business->reg_no &&
                        $business->bpra_verification_status === \App\Models\BusinessStatus::APPROVED &&
                        (count($directors) || count($shareholders) || count($shares)))
                    <div class="col-md-12 mb-3">
                        <div class="">
                            <span class="font-weight-bold text-uppercase">BPRA Verification</span>
                        </div>
                        <div class="pt-2">
                            <livewire:approval.bpra-verification :business="$business" />
                        </div>
                        <div class="col-md-12">
                            <div class="card-body mt-0 p-2 px-0">
                                <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist"
                                    style="margin-bottom: 0;">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="directors-tab" data-toggle="tab"
                                            href="#directors" role="tab" aria-controls="directors"
                                            aria-selected="true">Directors</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="shareholders-tab" data-toggle="tab"
                                            href="#shareholders" role="tab" aria-controls="shareholders"
                                            aria-selected="false">Shareholders</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="shares_distribution-tab" data-toggle="tab"
                                            href="#shares_distribution" role="tab"
                                            aria-controls="shares_distribution" aria-selected="false">Shares
                                            &
                                            Distribution</a>
                                    </li>
                                </ul>
                                <div class="tab-content bg-white border shadow-sm" id="myTabContent">
                                    <div class="tab-pane fade show active" id="directors" role="tabpanel"
                                        aria-labelledby="directors-tab">
                                        <div class="row m-1 p-3">
                                            <table class="table table-striped table-sm">
                                                <label class="font-weight-bold text-uppercase mt-2">Directors</label>
                                                <thead>
                                                    <th style="width: 29%">Name</th>
                                                    <th style="width: 16%">Phone</th>
                                                    <th style="width: 10%">Email</th>
                                                    <th style="width: 20%">Gender</th>
                                                    <th style="width: 25%">Location</th>
                                                </thead>
                                                <tbody>
                                                    @if (count($directors) > 0)
                                                        @foreach ($directors as $director)
                                                            <tr>
                                                                <td class="">
                                                                    {{ $director['first_name'] }}
                                                                    {{ $director['middle_name'] }}
                                                                    {{ $director['last_name'] }}
                                                                </td>
                                                                <td class="">
                                                                    {{ $director['mob_phone'] }}
                                                                </td>
                                                                <td class="">
                                                                    {{ $director['email'] }}
                                                                </td>
                                                                <td class="">
                                                                    @if (substr($director['gender'], 3) == 'M')
                                                                        MALE
                                                                    @elseif (substr($director['gender'], 3) == 'F')
                                                                        FEMALE
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td class="">
                                                                    {{ $director['city_name'] }}
                                                                    <div>
                                                                        {{ $director['first_line'] }}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="10" class="text-center">No Data</td>
                                                        </tr>
                                                    @endif

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                    <div class="tab-pane fade" id="shareholders" role="tabpanel"
                                        aria-labelledby="shareholders-tab">
                                        <div class="row m-1 p-3">
                                            <table class="table table-striped table-sm">
                                                <label
                                                    class="font-weight-bold text-uppercase mt-2">Shareholders</label>
                                                <thead>
                                                    <th style="width: 29%">Name</th>
                                                    <th style="width: 16%">Phone</th>
                                                    <th style="width: 10%">Email</th>
                                                    <th style="width: 20%">Gender</th>
                                                    <th style="width: 25%">Location</th>
                                                </thead>
                                                <tbody>
                                                    @if (count($shareholders) > 0)

                                                        @foreach ($shareholders as $shareholder)
                                                            <tr>
                                                                <td class="">
                                                                    {{ $shareholder['entity_name'] ? $shareholder['entity_name'] : $shareholder['first_name'] . ' ' . $shareholder['middle_name'] . ' ' . $shareholder['last_name'] ?? 'N/A' }}
                                                                </td>
                                                                <td class="">
                                                                    {{ $shareholder['mob_phone'] }}
                                                                </td>
                                                                <td class="">
                                                                    {{ $shareholder['email'] }}
                                                                </td>
                                                                <td class="">
                                                                    @if (substr($shareholder['gender'], 3) == 'M')
                                                                        MALE
                                                                    @elseif (substr($shareholder['gender'], 3) == 'F')
                                                                        FEMALE
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>

                                                                <td class="">
                                                                    @if ($shareholder['city_name'])
                                                                        {{ $shareholder['city_name'] }}
                                                                        <div>
                                                                            {{ $shareholder['first_line'] }}
                                                                        </div>
                                                                    @else
                                                                        N/A
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="10" class="text-center">No Data</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="shares_distribution" role="tabpanel"
                                        aria-labelledby="shares_distribution-tab">
                                        <div class="row m-1 p-3">
                                            <table class="table table-striped table-sm">
                                                <label class="font-weight-bold text-uppercase mt-2">Shares &
                                                    Distribution</label>
                                                <thead>
                                                    <th style="width: 30%">Ower Name</th>
                                                    <th style="width: 14%">No Of Shares</th>
                                                    <th style="width: 5%">Currency</th>
                                                    <th style="width: 23%">Shares Taken</th>
                                                    <th style="width: 23%">Shares Paid</th>
                                                </thead>
                                                <tbody>
                                                    @if (count($shares) > 0)

                                                        @foreach ($shares as $share)
                                                            <tr>
                                                                <td class="">
                                                                    {{ $share['shareholder_name'] }}
                                                                </td>
                                                                <td class="">
                                                                    {{ $share['number_of_shares'] }}
                                                                </td>
                                                                <td class="">
                                                                    {{ $share['currency'] }}
                                                                </td>
                                                                <td class="">
                                                                    {{ $share['number_of_shares_taken'] }}
                                                                </td>
                                                                <td class="">
                                                                    {{ $share['number_of_shares_paid'] }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="10" class="text-center">No Data</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                @endif
            </div>

        </div>

        <div class="tab-pane fade" id="location" role="tabpanel" aria-labelledby="location-tab">
            @if ($location = $business->headquarter)
                <div class="col-md-12 mt-3">
                    <h6 class="mb-0 font-weight-bold" style="flex: 1;">Headquarter</h6>
                    <hr class="mt-2 mb-3" />
                </div>
                <div class="row m-2">
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
                    @if ($location->effective_date)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Effective Date</span>
                            <p class="my-1">{{ $location->effective_date->toFormattedDateString() }}</p>
                        </div>
                    @endif
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Date of Commencing Business</span>
                        <p class="my-1">{{ $location->date_of_commencing->toFormattedDateString() }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Estimated Turnover (Last 12 Months)</span>
                        <p class="my-1">
                            @if ($location->pre_estimated_turnover)
                                {{ fmCurrency($location->pre_estimated_turnover) }} TZS
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Estimated Turnover (Next 12 Months)</span>
                        <p class="my-1">
                            @if ($location->post_estimated_turnover)
                                {{ fmCurrency($location->post_estimated_turnover) }} TZS
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Electric Metre No.</span>
                        <p class="my-1">{{ $location->meter_no ?? 'N/A' }}</p>
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
                        <p class="my-1">{{ $location->street->name }}</p>
                    </div>
                    @if ($location->po_box)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">PO Box</span>
                            <p class="my-1">{{ $location->po_box }}</p>
                        </div>
                    @endif
                    @if ($location->fax)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Fax Number</span>
                            <p class="my-1">{{ $location->fax }}</p>
                        </div>
                    @endif
                    @if ($location->physical_address)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Physical Address</span>
                            <p class="my-1">{{ $location->physical_address }}</p>
                        </div>
                    @endif
                    @if ($location->house_no)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">House No.</span>
                            <p class="my-1">{{ $location->house_no }}</p>
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
                    <div class="col-md-12 mt-1 d-flex justify-content-end mb-4">
                        @if ($location->status === \App\Models\BusinessStatus::APPROVED)
                            <div>
                                @foreach ($business->taxTypes as $type)
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
            @endif
            @if (count($business->branches))
                <div class="col-md-12">
                    <h6 class="pt-3 mb-0 font-weight-bold">Branches</h6>
                    <hr class="mt-2 mb-3" />
                </div>
                @foreach ($business->branches as $location)
                    <div class="row m-2">
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
                            <span class="font-weight-bold text-uppercase">Branch Name</span>
                            <p class="my-1">{{ $location->name }}</p>
                        </div>
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
                            <span class="font-weight-bold text-uppercase">Date of Commencing Business</span>
                            <p class="my-1">{{ $location->date_of_commencing->toFormattedDateString() }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Pre Estimated Turnover</span>
                            <p class="my-1">{{ $business->currency->iso }}
                                . {{ number_format($location->pre_estimated_turnover ?? 0, 2) }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Post Estimated Turnover</span>
                            <p class="my-1">{{ $business->currency->iso }}
                                . {{ number_format($location->post_estimated_turnover ?? 0, 2) }}</p>
                        </div>
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
                            <p class="my-1">{{ $location->street->name ?? 'N/A' }}</p>
                        </div>
                        @if ($location->physical_address)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Physical Address</span>
                                <p class="my-1">{{ $location->physical_address }}</p>
                            </div>
                        @endif
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
                            @if ($location->status === \App\Models\BranchStatus::APPROVED)
                                <div>
                                    @foreach ($business->taxTypes as $type)
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
                    <hr style="margin-top: -16px" class="mx-3" />
                @endforeach
            @endif
        </div>

        @if ($business->partners->count())
            <div class="tab-pane fade" id="partners" role="tabpanel" aria-labelledby="partners-tab">
                <div class="row m-2 pt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Reference No.</th>
                                <th>Mobile</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($business->partners as $partner)
                                <tr class="col-md-4 mb-3">
                                    <td class="font-weight-bold text-uppercase">{{ $partner->taxpayer->full_name }}
                                    </td>
                                    <td class="my-1">{{ $partner->taxpayer->reference_no }}</td>
                                    <td class="my-1">{{ $partner->taxpayer->mobile }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Responsible Person Name</span>
                    <p class="my-1">{{ $business->responsiblePerson->first_name }}
                        {{ $business->responsiblePerson->last_name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Responsible Person Ref No.</span>
                    <p class="my-1">{{ $business->responsiblePerson->reference_no }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Responsible Person Mobile No.</span>
                    <p class="my-1">{{ $business->responsiblePerson->mobile }}</p>
                </div>
                @if ($business->is_own_consultant)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Consultant Details</span>
                        <p class="my-1">{{ $business->taxpayer->first_name }} {{ $business->taxpayer->last_name }}
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Consultant Ref No.</span>
                        <p class="my-1">{{ $business->taxpayer->reference_no }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Consultant Mobile No.</span>
                        <p class="my-1">{{ $business->taxpayer->mobile }}</p>
                    </div>
                @elseif($consultant = $business->consultants()->latest()->first())
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Consultant Name</span>
                        <p class="my-1">{{ $consultant->taxpayer->first_name }}
                            {{ $consultant->taxpayer->last_name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Consultant Ref No.</span>
                        <p class="my-1">{{ $consultant->taxpayer->taxAgent->reference_no }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Consultant Mobile No.</span>
                        <p class="my-1">{{ $consultant->taxpayer->mobile }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Consultant Status</span>
                        @if ($consultant->status === 'pending')
                            <p class="my-1 text-info font-weight-bold">
                                <i class="bi bi-clock-history mr-1"></i>
                                Waiting Approval From Tax Agent
                            </p>
                        @elseif($consultant->status === 'approved')
                            <p class="my-1 text-success font-weight-bold">
                                <i class="bi bi-check-circle mr-1"></i>
                                Approved
                            </p>
                        @elseif($consultant->status === 'rejected')
                            <p class="my-1 text-danger font-weight-bold">
                                <i class="bi bi-x-circle-fill mr-1"></i>
                                Rejected
                            </p>
                        @elseif($consultant->status === 'removed')
                            <p class="my-1 text-danger font-weight-bold">
                                <i class="bi bi-trash-fill mr-1"></i>
                                Removed. Please assign new tax agent.
                            </p>
                        @endif
                    </div>
                @endif
                <div class="col-md-12 mt-3">
                    <h6 class="mb-0 font-weight-bold" style="flex: 1;">Business Registered By</h6>
                    <hr class="mt-2 mb-3" />
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Name</span>
                    <p class="my-1">{{ $business->taxpayer->first_name }} {{ $business->taxpayer->last_name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Ref No.</span>
                    <p class="my-1">{{ $business->taxpayer->reference_no }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Mobile No.</span>
                    <p class="my-1">{{ $business->taxpayer->mobile }}</p>
                </div>
            </div>
        </div>

        @if (count($business->banks))
            <div class="tab-pane fade" id="bank" role="tabpanel" aria-labelledby="bank-tab">
                @foreach ($business->banks as $bank)
                    <div class="row m-2">
                        <div class="col-md-4 mt-3">
                            <span class="font-weight-bold text-uppercase">Account No.</span>
                            <p class="my-1">{{ $bank->acc_no }}</p>
                        </div>
                        <div class="col-md-4 mt-3">
                            <span class="font-weight-bold text-uppercase">Account Type</span>
                            <p class="my-1">{{ $bank->accountType->name }}</p>
                        </div>
                        <div class="col-md-4 mt-3">
                            <span class="font-weight-bold text-uppercase">Currency</span>
                            <p class="my-1">{{ $bank->currency->iso }}</p>
                        </div>
                        <div class="col-md-4 mt-3">
                            <span class="font-weight-bold text-uppercase">Bank Name</span>
                            <p class="my-1">{{ $bank->bank->name }}</p>
                        </div>
                        <div class="col-md-4 mt-3">
                            <span class="font-weight-bold text-uppercase">Branch</span>
                            <p class="my-1">{{ $bank->branch }}</p>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($hotel = $business->hotel)
            <div class="tab-pane fade" id="hotel" role="tabpanel" aria-labelledby="hotel-tab">
                <div class="row m-2 pt-3">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Company Name</span>
                        <p class="my-1">{{ $hotel->company_name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Management Company</span>
                        <p class="my-1">{{ $hotel->management_company }}</p>
                    </div>
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
                        <span class="font-weight-bold text-uppercase">Average Charging Rate (Per night per person for
                            bed
                            and breakfast)</span>
                        <p class="my-1">{{ $hotel->average_rate }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Other Services</span>
                        <p class="my-1">{{ $hotel->other_services }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="tab-pane fade" id="business-attachment" role="tabpanel"
            aria-labelledby="business-attachment-tab">
            <div class="row pt-3 px-3">
                @foreach ($business->files as $file)
                    <div class="col-md-4">
                        <a class="file-item" target="_blank"
                            href="{{ route('business.file', encrypt($file->id)) }}">
                            <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                            <div style="font-weight: 500;" class="ml-1">
                                {{ $file->type->name ?? 'N/A' }}
                                @if ($file->type->short_name === \App\Models\BusinessFileType::TIN)
                                    - {{ $file->taxpayer->full_name }} (<b>{{ $file->taxpayer->reference_no }}</b>)
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
                @foreach ($business->partners as $partner)
                    @if ($partner->tin)
                        <div class="col-md-4">
                            <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                                class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                <a target="_blank"
                                    href="{{ route('business.tin.file', encrypt($partner->taxpayer_id)) }}"
                                    style="font-weight: 500;" class="ml-1">
                                    TIN Certificate - {{ $partner->taxpayer->full_name }}
                                    (<b>{{ $partner->taxpayer->reference_no }}</b>)
                                    <i class="bi bi-arrow-up-right-square ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
                @if ($business->taxpayer->tin_location)
                    <div class="col-md-4">
                        <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                            class="p-2 mb-3 d-flex rounded-sm align-items-center">
                            <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                            <a target="_blank"
                                href="{{ route('business.tin.file', encrypt($business->taxpayer_id)) }}"
                                style="font-weight: 500;" class="ml-1">
                                TIN Certificate - {{ $business->taxpayer->full_name }}
                                (<b>{{ $business->taxpayer->reference_no }}</b>)
                                <i class="bi bi-arrow-up-right-square ml-1"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
