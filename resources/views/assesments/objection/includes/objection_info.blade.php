@if ($objection->status === \App\Models\ObjectionStatus::CORRECTION)
    <livewire:approval.approval-processing modelName='App\Models\objection' modelId="{{ encrypt($objection->id) }}" />
@endif
<ul class="nav nav-tabs shadow-sm mb-0" id="myTab" role="tablist">
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
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="attachment-tab" data-toggle="tab" href="#attachment" role="tab"
            aria-controls="attachment" aria-selected="false">Attachments</a>
    </li>

</ul>
<div class="tab-content bg-white border shadow-sm" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="row m-2 pt-3">
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Objection Status</span>
                <p class="my-1">
                    @if ($objection->status === \App\Models\ObjectionStatus::APPROVED)
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
                <span class="font-weight-bold text-uppercase">Tax Deposit</span>
                <p class="my-1">{{ $objection->bill->amount }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Control No</span>
                <p class="my-1">{{ $objection->bill->control_number }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <p class="my-1">
                    <a target="_blank" href="{{ route("bill.invoice", encrypt($objection->bill->id)) }}"
                        class="btn btn-primary btn-sm pl-3 pr-4 font-weight-bold">
                        <i class="bi bi-download mr-3"></i><u>Download Bill</u>
                    </a>
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

        </div>
    </div>

    <div class="tab-pane fade" id="dispute" role="tabpanel" aria-labelledby="dispute-tab">
        {{-- @if ($dispute = $business->headquarter) --}}
        <div class="col-md-12 mt-1">
            <h6 class="pt-3 mb-0 font-weight-bold">Assesment</h6>
            <hr class="mt-2 mb-3" />
        </div>
        <div class="row m-2">
            {{-- <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Assesment No</span>
                <p class="my-1"></p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Assesment Date</span>
                <p class="my-1">{{ $dispute->owner_name }}</p>
            </div> --}}
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
                <p class="my-1">{{ $objection->tax_in_dispute + $objection->tax_not_in_dispute }} TZS</p>
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

    <div class="tab-pane fade" id="attachment" role="tabpanel" aria-labelledby="attachment-tab">
        <div class="row m-2 pt-3">
            @foreach ($files as $file)
                <div class="col-md-4">
                    <a class="file-item" target="_blank" href="">
                        <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                        <div class="ml-1 font-weight-bold">
                            {{ $file["file_name"] }}
                        </div>
                    </a>
                </div>
            @endforeach

        </div>
    </div>

</div>

@if ($objection->taxVerificationAssesment)
    <div class="card my-2">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Assessment Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Principal Amount</span>
                    <p class="my-1">
                        {{ number_format($objection->taxVerificationAssesment->principal_amount ?? 0, 2) }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Penalty Amount</span>
                    <p class="my-1">{{ number_format($objection->taxVerificationAssesment->penalty_amount ?? 0, 2) }}
                    </p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Interest Amount</span>
                    <p class="my-1">
                        {{ number_format($objection->taxVerificationAssesment->interest_amount ?? 0, 2) }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Total Amount Due</span>
                    <p class="my-1">{{ number_format($objection->taxVerificationAssesment->total_amount ?? 0, 2) }}
                    </p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
                    <p class="my-1">{{ number_format($objection->taxVerificationAssesment->outstanding_amount ?? 0, 2) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif
@if ($objection->objection_report)
    <div class="card my-4 rounded-0">
        <div class="card-header font-weight-bold bg-white">
            Objection Report
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="p-2 mb-3 d-flex rounded-sm align-items-center highlighted-file-box">
                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                        <a target="_blank"
                            href="{{ route("assesments.waiver.files", encrypt($objection->objection_report)) }}"
                            class="ml-1 font-weight-bold">
                            Objection Report
                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                        </a>
                    </div>
                </div>

                @if ($objection->notice_report)
                    <div class="col-md-3">
                        <div class="p-2 mb-3 d-flex rounded-sm align-items-center highlighted-file-box">
                            <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                            <a target="_blank"
                                href="{{ route("assesments.waiver.files", encrypt($objection->notice_report)) }}"
                                class="ml-1 font-weight-bold">
                                Notice Report
                                <i class="bi bi-arrow-up-right-square ml-1"></i>
                            </a>
                        </div>
                    </div>
                @endif

                @if ($objection->setting_report)
                    <div class="col-md-3">
                        <div class="p-2 mb-3 d-flex rounded-sm align-items-center highlighted-file-box">
                            <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                            <a target="_blank"
                                href="{{ route("assesments.waiver.files", encrypt($objection->setting_report)) }}"
                                class="ml-1 font-weight-bold">
                                Setting Report
                                <i class="bi bi-arrow-up-right-square ml-1"></i>
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endif
