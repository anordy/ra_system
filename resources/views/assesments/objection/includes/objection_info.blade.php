
    @if ($objection->status === \App\Models\ObjectionStatus::CORRECTION)
        <livewire:approval.approval-processing modelName='App\Models\objection' modelId="{{ $objection->id }}" />
    @endif
    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                aria-selected="true">Complainant</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="dispute-tab" data-toggle="tab" href="#dispute" role="tab" aria-controls="dispute"
                aria-selected="false">Tax In Dispute</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link" id="ground-tab" data-toggle="tab" href="#ground" role="tab" aria-controls="ground"
                aria-selected="false">Ground objection</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="reason-tab" data-toggle="tab" href="#reason" role="tab" aria-controls="reason"
                aria-selected="false">Reason for Ground</a>
        </li>

    </ul>
    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row m-2 pt-3">
                     <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Objection Status</span>
                    <p class="my-1">
                        @if($objection->status === \App\Models\ObjectionStatus::APPROVED)
                            <span class="font-weight-bold text-success">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Approved
                            </span>
                        @elseif($objection->status === \App\Models\ObjectionStatus::REJECTED)
                            <span class="font-weight-bold text-danger">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Rejected
                            </span>
                        @elseif($objection->status === \App\Models\ObjectionStatus::CORRECTION)
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
                @if($business->business_type === \App\Models\BusinessType::HOTEL)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Type</span>
                        <p class="my-1">Hotel</p>
                    </div>
                @endif
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Identification No. (TIN)</span>
                    <p class="my-1">{{ $business->tin }}</p>
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
                    <span class="font-weight-bold text-uppercase">Physical Address</span>
                    <p class="my-1">{{ $business->physical_address }}</p>
                </div>

            </div>
        </div>

        <div class="tab-pane fade" id="dispute" role="tabpanel" aria-labelledby="dispute-tab">
            {{-- @if ($dispute = $business->headquarter) --}}
            <div class="col-md-12 mt-1">
                <h6 class="pt-3 mb-0 font-weight-bold">Assesment</h6>
                <hr class="mt-2 mb-3" />
            </div>
            <div class="row m-2">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Assesment No</span>
                    {{-- <p class="my-1"></p> --}}
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Assesment Date</span>
                    {{-- <p class="my-1">{{ $dispute->owner_name }}</p> --}}
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Amount In Dispute</span>
                    <p class="my-1">{{ $objection->tax_in_dispute }} Tzs</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Amount Not in Dispute</span>
                    <p class="my-1">{{ $objection->tax_not_in_dispute }} Tzs</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Amount Objected</span>
                    <p class="my-1">{{ $objection->tax_in_dispute }} TZS</p>
                </div>


            </div>
            {{-- @endif --}}

        </div>

        <div class="tab-pane fade" id="ground" role="tabpanel" aria-labelledby="ground-tab">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Grounds for objection</span>
                    <p class="my-1">{{ $objection->ground_objection }}</p>

                </div>

            </div>
        </div>


        <div class="tab-pane fade" id="reason" role="tabpanel" aria-labelledby="reason-tab">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Reason for objection</span>
                    <p class="my-1">{{ $objection->reason_objection }}</p>
                </div>

            </div>
        </div>
 
    </div>

    <div class="card shadow-sm my-4 rounded-0">
        <div class="card-header font-weight-bold bg-white">
            objection Attachments
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <a class="file-item" target="_blank" href="">
                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                        <div style="font-weight: 500;" class="ml-1">
                            Ground

                        </div>
                    </a>
                </div>

                  <div class="col-md-4">
                    <a class="file-item" target="_blank" href="">
                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                        <div style="font-weight: 500;" class="ml-1">
                            Ground

                        </div>
                    </a>
                </div>

                  <div class="col-md-4">
                    <a class="file-item" target="_blank" href="">
                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                        <div style="font-weight: 500;" class="ml-1">
                            Ground

                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>
 
