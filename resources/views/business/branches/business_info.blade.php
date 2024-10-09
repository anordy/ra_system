<div class="row">
    <ul class="nav nav-tabs shadow-sm mb-0" id="myTab" role="tablist">
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
                aria-controls="contact" aria-selected="false">Assistants & Tax Consultants</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank" role="tab" aria-controls="bank"
                aria-selected="false">Bank Account</a>
        </li>
        @if ($business->hotel)
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="hotel-tab" data-toggle="tab" href="#hotel" role="tab" aria-controls="hotel"
                    aria-selected="false">Hotel Information</a>
            </li>
        @endif
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="document-tab" data-toggle="tab" href="#document" role="tab"
                aria-controls="document" aria-selected="false">Business Attachments</a>
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
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Reg. No.</span>
                    <p class="my-1">{{ $business->reg_no ?? "N/A" }} </p>
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
                        @foreach ($business->taxTypes as $type)
                            {{ $type->name }};
                        @endforeach
                    </p>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="location" role="tabpanel" aria-labelledby="location-tab">
            @if ($location = $business->headquarter)
                <div class="col-md-12 mt-1 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold flex-grow-1">Headquarter</h6>
                    <a class="btn btn-outline-success btn-sm mt-1">
                        <i class="bi bi-patch-check"></i>
                        Print Certificate
                    </a>
                </div>
                <div class="col-md-12">
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
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Electric Metre No.</span>
                        <p class="my-1">{{ $location->meter_no }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Pre Estimated Turnover</span>
                        <p class="my-1">{{ number_format($location->pre_estimated_turnover ?? 0, 2) }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Post Estimated Turnover</span>
                        <p class="my-1">{{ number_format($location->post_estimated_turnover ?? 0, 2) }}</p>
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
                        <p class="my-1">{{ $location->street->name ?? "N/A" }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Physical Address</span>
                        <p class="my-1">{{ $location->physical_address }}</p>
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
                        <p class="my-1">{{ $business->taxpayer->first_name }}
                            {{ $business->taxpayer->last_name }}</p>
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
            </div>
        </div>

        @if ($bank = $business->bank)
            <div class="tab-pane fade" id="bank" role="tabpanel" aria-labelledby="bank-tab">
                <div class="row m-2 pt-3">
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
                        <span class="font-weight-bold text-uppercase">Nature of Location</span>
                        <p class="my-1">{{ $hotel->nature->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Hotel Star Rating</span>
                        <p class="my-1">{{ $hotel->star->name ?? 'N/A' }}</p>
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
                            bed and breakfast)</span>
                        <p class="my-1">{{ $hotel->average_rate }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Other Services</span>
                        <p class="my-1">{{ $hotel->other_services }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="tab-pane fade" id="document" role="tabpanel" aria-labelledby="document-tab">
            <div class="row m-2 pt-3">
                @foreach ($business->files as $file)
                    <div class="col-md-4">
                        <a class="file-item file-blue-border" target="_blank"
                            href="{{ route("business.file", encrypt($file->id)) }}">
                            <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                            <div class="ml-1 font-weight-bold">
                                {{ $file->type->name }}
                                @if ($file->type->short_name === \App\Models\BusinessFileType::TIN)
                                    - {{ $file->taxpayer->full_name }}
                                    (<b>{{ $file->taxpayer->reference_no }}</b>)
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
                @foreach ($business->partners as $partner)
                    @if ($partner->tin)
                        <div class="col-md-4">
                            <div
                                class="p-2 mb-3 d-flex rounded-sm align-items-center file-blue-border">
                                <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                                <a href="#" class="ml-1 font-weight-bold">
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
                        <div class="p-2 mb-3 d-flex rounded-sm align-items-center file-blue-border">
                            <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                            <a href="#" class="ml-1 font-weight-bold">
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
