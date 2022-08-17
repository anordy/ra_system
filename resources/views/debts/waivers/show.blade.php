<div class="mt-4 mx-4">
    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                aria-selected="true">Complainant</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link" id="ground-tab" data-toggle="tab" href="#ground" role="tab" aria-controls="ground"
                aria-selected="false">Ground waiver</a>
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
                    <span class="font-weight-bold text-uppercase">waiver Status</span>
                    <p class="my-1">
                        @if ($waiver->status === \App\Models\WaiverStatus::APPROVED)
                            <span class="font-weight-bold text-success">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Approved
                            </span>
                        @elseif($waiver->status === \App\Models\WaiverStatus::REJECTED)
                            <span class="font-weight-bold text-danger">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Rejected
                            </span>
                        @elseif($waiver->status === \App\Models\WaiverStatus::CORRECTION)
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
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Waiver Type</span>
                    <p class="my-1">
                            <span class="badge badge-success py-1 px-2"
                                style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                                {{ $waiver->category }}
                            </span>
                    </p>
                </div>

            </div>
        </div>

        <div class="tab-pane fade" id="ground" role="tabpanel" aria-labelledby="ground-tab">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Grounds for waiver</span>
                    <p class="my-1">{{ $waiver->ground }}</p>
                </div>
            </div>
        </div>


        <div class="tab-pane fade" id="reason" role="tabpanel" aria-labelledby="reason-tab">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Reason for waiver</span>
                    <p class="my-1">{{ $waiver->reason }}</p>
                </div>

            </div>
        </div>

        <div class="tab-pane fade" id="attachment" role="tabpanel" aria-labelledby="attachment-tab">
            <div class="row m-2 pt-3">
                @foreach ($files as $file)
                    <div class="col-md-3">
                        <div style="background: #faf5f5; color: #863d3c; border: .5px solid #863d3c24;"
                            class="p-2 mb-3 d-flex rounded-sm align-items-center">
                            <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                            {{-- <a target="_blank"
                                href="{{ route('debts.assesment.files', encrypt($file['file_path'])) }}"
                                style="font-weight: 500;" class="ml-1">
                                {{ $file['file_name'] }}
                                <i class="bi bi-arrow-up-right-square ml-1"></i>
                            </a> --}}
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>


    @if ($debt)
        <div class="card my-4 rounded-0">
            <div class="card-header text-uppercase font-weight-bold bg-white">
                Assessment Details
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Principal Amount</span>
                        <p class="my-1">{{ number_format($debt->principal_amount, 2) ?? '' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Penalty Amount</span>
                        <p class="my-1">{{ number_format($debt->penalty, 2) ?? '' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Interest Amount</span>
                        <p class="my-1">{{ number_format($debt->interest, 2) ?? '' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Total Amount Due</span>
                        <p class="my-1">{{ number_format($debt->total_amount, 2) ?? '' }}</p>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>
