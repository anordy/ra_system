<div>
    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                aria-selected="true">Complainant</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="dispute-tab" data-toggle="tab" href="#dispute" role="tab"
                aria-controls="dispute" aria-selected="false">Tax In Dispute</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="assessment-histories-tab" data-toggle="tab" href="#assessment-histories" role="tab"
                aria-controls="assessment-histories" aria-selected="false">Assessment History</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link" id="ground-tab" data-toggle="tab" href="#ground" role="tab" aria-controls="ground"
                aria-selected="false">Ground dispute</a>
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
                    <span class="font-weight-bold text-uppercase">dispute Status</span>
                    <p class="my-1">
                        @if ($dispute->app_status === \App\Enum\DisputeStatus::APPROVED)
                            <span class="font-weight-bold text-success">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Approved
                            </span>
                        @elseif($dispute->app_status === \App\Enum\DisputeStatus::REJECTED)
                            <span class="font-weight-bold text-danger">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Rejected
                            </span>
                        @elseif($dispute->app_status === \App\Enum\DisputeStatus::CORRECTION)
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
                    <span class="font-weight-bold text-uppercase">Dispute Category</span> <br>
                    <span class="badge badge-danger py-1 px-2"
                        style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
                        <i class="bi bi-clock-history mr-1"></i>
                        {{ $dispute->category }}
                    </span>
                </div>
                @if ($dispute->waiver_category)
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Waiver Type</span>
                    <p class="my-1">
                        @if ($dispute->waiver_category == 'interest')
                            Interest
                        @elseif($dispute->waiver_category == 'penalty')
                            Penalty
                        @else
                            Penalty & Interest
                        @endif
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Waived Penalty Rate</span>
                    <p class="my-1">{{ $dispute->penalty_rate }}%</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Waived Interest Rate</span>
                    <p class="my-1">{{ $dispute->interest_rate }}%</p>
                </div>
                @endif
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
                    <p class="my-1">{{ $business->reg_no ?? 'N/A' }} </p>
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
                <h6 class="pt-3 mb-0 font-weight-bold">Disputes</h6>
                <hr class="mt-2 mb-3" />
            </div>
            <div class="row m-2">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Amount In Dispute</span>
                    <p class="my-1">{{ number_format($dispute->tax_in_dispute,2) }} Tzs</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Amount Not in Dispute</span>
                    <p class="my-1">{{ number_format($dispute->tax_not_in_dispute,2) }} Tzs</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Assesed Amount</span>
                    <p class="my-1">{{ number_format($dispute->tax_in_dispute + $dispute->tax_not_in_dispute,2) }} TZS</p>
                </div>


            </div>
            {{-- @endif --}}

        </div>

        <div class="tab-pane fade" id="assessment-histories" role="tabpanel" aria-labelledby="assessment-histories-tab">
            <div class="col-md-12 mt-1">
                <h6 class="pt-3 mb-0 font-weight-bold">Assessment History</h6>
                <hr class="mt-2 mb-3" />
                <livewire:assesments.assessment-history-table modelName='App\Models\TaxAssessments\TaxAssessmentHistory'
                        modelId="{{ encrypt($assesment->id) }}" />
            </div>
        </div>

        <div class="tab-pane fade" id="ground" role="tabpanel" aria-labelledby="ground-tab">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Grounds for dispute</span>
                    <p class="my-1">{{ $dispute->ground }}</p>

                </div>

            </div>
        </div>


        <div class="tab-pane fade" id="reason" role="tabpanel" aria-labelledby="reason-tab">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Reason for dispute</span>
                    <p class="my-1">{{ $dispute->reason }}</p>
                </div>

            </div>
        </div>

        <div class="tab-pane fade" id="attachment" role="tabpanel" aria-labelledby="attachment-tab">
            <div class="row m-2 pt-3">
                @foreach ($files as $file)
                    <div class="col-md-3">
                        <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                            class="p-2 mb-3 d-flex rounded-sm align-items-center">
                            <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                            <a target="_blank"
                                href="{{ route('assesments.waiver.files', encrypt($file['file_path'])) }}"
                                style="font-weight: 500;" class="ml-1">
                                {{ $file['file_name'] }}
                                <i class="bi bi-arrow-up-right-square ml-1"></i>
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>


    @if ($assesment)
        <div class="card my-4 rounded-0">
            <div class="card-header text-uppercase font-weight-bold bg-white">
                Assessment Details
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Principal Amount</span>
                        <p class="my-1">{{ number_format($assesment->principal_amount,2) ?? '' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Penalty Amount</span>
                        <p class="my-1">{{ number_format($assesment->penalty_amount,2) ?? '' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Interest Amount</span>
                        <p class="my-1">{{ number_format($assesment->interest_amount,2) ?? '' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Total Amount Due</span>
                        <p class="my-1">{{ number_format($assesment->total_amount ,2)?? '' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
                        <p class="my-1">{{ number_format($assesment->outstanding_amount ,2)?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($dispute->dispute_report)
        <div class="card my-4 rounded-0">
            <div class="card-header font-weight-bold bg-white">
                DISPUTE REPORT
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                            class="p-2 mb-3 d-flex rounded-sm align-items-center">
                            <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                            <a target="_blank"
                                href="{{ route('assesments.waiver.files', encrypt($dispute->dispute_report)) }}"
                                style="font-weight: 500;" class="ml-1">
                                dispute Report
                                <i class="bi bi-arrow-up-right-square ml-1"></i>
                            </a>
                        </div>
                    </div>


                    @if ($dispute->notice_report)
                        <div class="col-md-3">
                            <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                                class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                <a target="_blank"
                                    href="{{ route('assesments.waiver.files', encrypt($dispute->notice_report)) }}"
                                    style="font-weight: 500;" class="ml-1">
                                    Notice Report
                                    <i class="bi bi-arrow-up-right-square ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @endif


                    @if ($dispute->setting_report)
                        <div class="col-md-3">
                            <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                                class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                <a target="_blank"
                                    href="{{ route('assesments.waiver.files', encrypt($dispute->setting_report)) }}"
                                    style="font-weight: 500;" class="ml-1">
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
</div>
