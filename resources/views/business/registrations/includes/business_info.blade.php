<div class="pt-3 px-2">
    <ul class="nav nav-tabs shadow-sm mb-0">
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
        @if ($business->reg_no && $business->bpra_verification_status === \App\Models\BusinessStatus::APPROVED &&
                (count($directors) || count($shareholders) || count($shares)))
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="bpra-information-tab" data-toggle="tab" href="#bpra-information"
                        role="tab" aria-controls="bpra-information" aria-selected="false">BPRA Information</a>
                </li>
        @endif
        @if($business->tin && $business->tininformation && $business->tin_verification_status === \App\Enum\TinVerificationStatus::APPROVED)
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tin-information-tab" data-toggle="tab" href="#tin-information"
                   role="tab" aria-controls="tin-information" aria-selected="false">TIN Information</a>
            </li>
        @endif
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
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Types</span>
                    <p class="my-1">
                        @foreach ($business->taxTypes as $type)
                            @if($type->pivot->sub_vat_id)
                                {{ \App\Models\Returns\Vat\SubVat::find($type->pivot->sub_vat_id, ['name'])->name ?? 'N/A' }} ({{ $type->pivot->currency ?? 'N/A' }});
                            @else
                                {{ $type->name }} ({{ $type->pivot->currency ?? 'N/A' }});
                            @endif
                        @endforeach
                    </p>
                </div>

                @if($business->lumpsumPayment)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Lumpsum Annual Estimate</span>
                        <p class="my-1">{{ number_format($business->lumpsumPayment->annual_estimate, 2) ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Payment Quarters</span>
                        <p class="my-1">{{ $business->lumpsumPayment->payment_quarters ?? 'N/A' }}</p>
                    </div>
                @endif

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

        <div class="tab-pane fade" id="location" role="tabpanel" aria-labelledby="location-tab">
            @if ($location = $business->headquarter)
                <div class="col-md-12 mt-3">
                    <h6 class="mb-0 font-weight-bold">Headquarter</h6>
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
                    @if($location->vfms_associated_at)
                        <div class="row col-md-12">
                            <livewire:business.vfms.business-unit-details :location="$location" />
                        </div>
                    @endif
                    <div class="col-md-12 mt-1 d-flex justify-content-end mb-3">
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
                    <hr>
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
                        @if ($location->owner_phone_no)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Owner's Mobile</span>
                                <p class="my-1">{{ $location->owner_phone_no }}</p>
                            </div>
                        @endif
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Date of Commencing Business</span>
                            <p class="my-1">{{ $location->date_of_commencing->toFormattedDateString() }}</p>
                        </div>
                        @if ($location->effective_date)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Effective Date</span>
                                <p class="my-1">{{ $location->effective_date->toFormattedDateString() }}</p>
                            </div>
                        @endif
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Estimated Turnover (Last 12 Months)</span>
                            <p class="my-1">{{ $business->currency->iso }}
                                . {{ number_format($location->pre_estimated_turnover ?? 0, 2) }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Estimated Turnover (Next 12 Months)</span>
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
                        @if($location->latitude)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Latitude</span>
                                <p class="my-1">{{ $location->latitude }}</p>
                            </div>
                        @endif
                        @if($location->longitude)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Longitude</span>
                                <p class="my-1">{{ $location->longitude }}</p>
                            </div>
                        @endif
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
                        @if($location->vfms_associated_at)
                            <div class="row col-md-12">
                                <livewire:business.vfms.business-unit-details :location="$location" />
                            </div>
                        @endif
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
                    <hr class="mx-3" />
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
            <div class="col-md-12 mt-4">
                <span class="pt-3 mb-0 font-weight-bold">{{ __('Assistants') }}</span>
                <hr class="mt-2 mb-3" />
            </div>
            <div class="row m-2 mb-3">
                @if($business->assistants()->count())
                    @foreach($business->assistants()->active()->with('taxpayer')->get() as $index => $assistant)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">{{$index + 1}}. {{ __('Name') }}</span>
                            <p class="my-1">{{ $assistant->taxpayer->fullName }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Ref No') }}.</span>
                            <p class="my-1">{{ $assistant->taxpayer->reference_no }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Mobile No') }}.</span>
                            <p class="my-1">{{ $assistant->taxpayer->mobile }}</p>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12 text-center text-muted py-3">N/A</div>
                @endif
            </div>
            <div class="col-md-12 mt-1">
                <span class="pt-3 mb-0 font-weight-bold">{{ __('Tax Consultant') }}</span>
                <hr class="mt-2 mb-3" />
            </div>
            <div class="row m-2">
                @if($consultant = $business->consultants()->latest()->first())
                    @if ($consultant->status !== 'removed')
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Consultant Name') }}</span>
                            <p class="my-1">{{ $consultant->taxpayer->first_name }}
                                {{ $consultant->taxpayer->last_name }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Consultant Ref No') }}.</span>
                            <p class="my-1">{{ $consultant->taxpayer->taxAgent->reference_no }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Consultant Mobile No') }}.</span>
                            <p class="my-1">{{ $consultant->taxpayer->mobile }}</p>
                        </div>
                    @endif

                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">{{ __('Consultant Status') }}</span>
                        @if ($consultant->status === 'pending')
                            <p class="my-1 text-info font-weight-bold">
                                <i class="bi bi-clock-history mr-1"></i>
                                {{ __('Waiting Approval From Tax Agent') }}
                            </p>
                        @elseif($consultant->status === 'approved')
                            <p class="my-1 text-success font-weight-bold">
                                <i class="bi bi-check-circle mr-1"></i>
                                {{ __('Approved') }}
                            </p>
                        @elseif($consultant->status === 'rejected')
                            <p class="my-1 text-danger font-weight-bold">
                                <i class="bi bi-x-circle-fill mr-1"></i>
                                {{ __('Rejected') }}
                            </p>
                        @elseif($consultant->status === 'removed')
                            <p class="my-1 text-danger font-weight-bold">
                                <i class="bi bi-trash-fill mr-1"></i>
                                {{ __('Removed. Please assign new tax agent') }}.
                            </p>
                        @endif
                    </div>
                @else
                    <div class="col-md-12 text-center py-3 text-muted">
                        N/A
                    </div>
                @endif
            </div>
            <div class="row m-2">
                <div class="col-md-12">
                    <span class="mb-0 font-weight-bold">Business Registered By</span>
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
                        <p class="my-1">{{ $hotel->company_name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Management Company</span>
                        <p class="my-1">{{ $hotel->management_company ?? 'N/A'}}</p>
                    </div>
                    @if ($hotel->business_reg_no)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Registration No</span>
                            <p class="my-1">{{ $hotel->business_reg_no }}</p>
                        </div>
                    @endif
                    @if ($hotel->old_business_reg_no)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Old Business Registration No</span>
                        <p class="my-1">{{ $hotel->old_business_reg_no }}</p>
                    </div>
                @endif
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Hotel Location</span>
                        <p class="my-1">{{ $hotel->hotel_location }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Hotel Star Rating</span>
                        <p class="my-1">{{ $hotel->star->name ?? 'N/A' }}</p>
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
                        <span class="font-weight-bold text-uppercase">Average Charging Rate (Per night per person for bed and breakfast)</span>
                        <p class="my-1">{{ $hotel->average_rate ? number_format($hotel->average_rate) : 'N/A' }} {{ $business->currency->iso }}</p>
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
                            href="{{ route("business.file", encrypt($file->id)) }}">
                            <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                            <div class="ml-1 font-weight-bold">
                                {{ $file->type->name ?? "N/A" }}
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
                            <div class="file-blue-border p-2 mb-3 d-flex rounded-sm align-items-center">
                                <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                                <a target="_blank"
                                    href="{{ route("business.tin.file", encrypt($partner->taxpayer_id)) }}"
                                    class="ml-1 font-weight-bold">
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
                            <a target="_blank"
                                href="{{ route('business.tin.file', encrypt($business->taxpayer_id)) }}"
                                class="ml-1 font-weight-bold">
                                TIN Certificate - {{ $business->taxpayer->full_name }}
                                (<b>{{ $business->taxpayer->reference_no }}</b>)
                                <i class="bi bi-arrow-up-right-square ml-1"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if ($business->reg_no && $business->bpra_verification_status === \App\Models\BusinessStatus::APPROVED &&
            (count($directors) || count($shareholders) || count($shares)))
            <div class="tab-pane fade p-4" id="bpra-information" role="tabpanel"
                aria-labelledby="bpra-information-tab">
                <div class="pt-2">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business No.</span>
                            <p class="my-1">{{ $business->reg_no ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Name</span>
                            <p class="my-1">{{ $business->name }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Status</span>
                            @if ($business->bpra_verification_status === \App\Models\BusinessStatus::PENDING)
                                <p class="my-1">
                                    <span class="badge badge-danger py-1 px-2 text-capitalize pending-status">
                                        <i class="bi bi-record-circle mr-1"></i>
                                        {{ $business->bpra_verification_status }}
                                    </span>
                                </p>
                            @elseif ($business->bpra_verification_status === \App\Models\BusinessStatus::PBRA_UNVERIFIED)
                                <p class="my-1">
                                    <span class="badge badge-danger py-1 px-2 text-capitalize pending-status">
                                        <i class="bi bi-record-circle mr-1"></i>
                                        {{ $business->bpra_verification_status }}
                                    </span>
                                </p>
                            @elseif($business->bpra_verification_status === \App\Models\BusinessStatus::APPROVED)
                                <p class="my-1">
                                    <span class="badge badge-success py-1 px-2 green-status">
                                        <i class="bi bi-check-circle-fill mr-1"></i>
                                        Verification Successful
                                    </span>
                                </p>
                            @endif
                        </div>

                    </div>
                </div>
                <table class="table table-striped table-sm table-bordered">
                    <label class="font-weight-bold text-uppercase mt-2">Directors</label>
                    <thead>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Location</th>
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
                                        @if (substr($director['gender'] ?? '', 3) == 'M')
                                            MALE
                                        @elseif (substr($director['gender'] ?? '', 3) == 'F')
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
                <table class="table table-striped table-sm table-bordered">
                    <label
                        class="font-weight-bold text-uppercase mt-2">Shareholders</label>
                    <thead>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Location</th>
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
                                        @if (substr($shareholder['gender'] ?? '', 3) == 'M')
                                            MALE
                                        @elseif (substr($shareholder['gender'] ?? '', 3) == 'F')
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
                <table class="table table-striped table-sm table-bordered">
                    <label class="font-weight-bold text-uppercase mt-2">Shares &
                        Distribution</label>
                    <thead>
                        <th>Ower Name</th>
                        <th>No Of Shares</th>
                        <th>Currency</th>
                        <th>Shares Taken</th>
                        <th>Shares Paid</th>
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
        @endif

        @if($business->tin && $business->tininformation && $business->tin_verification_status === \App\Enum\TinVerificationStatus::APPROVED)
            <div class="tab-pane fade p-4" id="tin-information" role="tabpanel"
             aria-labelledby="tin-information-tab">
            <div class="pt-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body mt-0 p-2">
                            <div class="row my-2">
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">TIN</span>
                                    <p class="my-1">{{ $business->tininformation->tin }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">First Name</span>
                                    <p class="my-1">{{ $business->tininformation->first_name }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Middle Name</span>
                                    <p class="my-1">{{ $business->tininformation->middle_name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Last Name</span>
                                    <p class="my-1">{{ $business->tininformation->last_name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Email</span>
                                    <p class="my-1">{{ $business->tininformation->email ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Mobile</span>
                                    <p class="my-1">{{ $business->tininformation->mobile ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Gender</span>
                                    <p class="my-1">{{ $business->tininformation->gender }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Date of Birth</span>
                                    <p class="my-1">{{ $business->tininformation->date_of_birth ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Nationality</span>
                                    <p class="my-1">{{ $business->tininformation->nationality ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Taxpayer Name</span>
                                    <p class="my-1">{{ $business->tininformation->taxpayer_name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Trading Name</span>
                                    <p class="my-1">{{ $business->tininformation->trading_name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">District</span>
                                    <p class="my-1">{{ $business->tininformation->district }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Region</span>
                                    <p class="my-1">{{ $business->tininformation->region }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Street</span>
                                    <p class="my-1">{{ $business->tininformation->street }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Postal City</span>
                                    <p class="my-1">{{ $business->tininformation->postal_city ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Plot Number</span>
                                    <p class="my-1">{{ $business->tininformation->plot_number ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Block Number</span>
                                    <p class="my-1">{{ $business->tininformation->block_number ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Vat Registration Number</span>
                                    <p class="my-1">{{ $business->tininformation->vat_registration_number ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Status</span>
                                    <p class="my-1">{{ $business->tininformation->status ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Is Business TIN</span>
                                    <p class="my-1">{{ $business->tininformation->is_business_tin == 1 ? 'Yes' : 'No' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Is Entity TIN</span>
                                    <p class="my-1">{{ $business->tininformation->is_entity_tin == 1 ? 'Yes' : 'No' }}</p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
        @endif
    </div>
</div>
