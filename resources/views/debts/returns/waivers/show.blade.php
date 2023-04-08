<div class="mt-4 mx-4">
    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                aria-selected="true">Waiver Details</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link" id="ground-tab" data-toggle="tab" href="#ground" role="tab" aria-controls="ground"
                aria-selected="false">Reasons For Waiver</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="attachment-tab" data-toggle="tab" href="#attachment" role="tab"
                aria-controls="attachment" aria-selected="false">Attachments</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="approval-tab" data-toggle="tab" href="#approval" role="tab"
                aria-controls="approval" aria-selected="false">Approval History</a>
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
                    <span class="font-weight-bold text-uppercase">ZTN Number</span>
                    <p class="my-1">{{ $waiver->debt->business->ztn_number ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $waiver->debt->business->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Category</span>
                    <p class="my-1">{{ $waiver->debt->business->category->name }}</p>
                </div>
                @if ($waiver->debt->business->business_type === \App\Models\BusinessType::HOTEL)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Type</span>
                        <p class="my-1">Hotel</p>
                    </div>
                @endif
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Identification No. (TIN)</span>
                    <p class="my-1">{{ $waiver->debt->business->tin }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Reg. No.</span>
                    <p class="my-1">{{ $waiver->debt->business->reg_no }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Owner Designation</span>
                    <p class="my-1">{{ $waiver->debt->business->owner_designation }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Mobile</span>
                    <p class="my-1">{{ $waiver->debt->business->mobile }}</p>
                </div>
                @if ($waiver->debt->business->alt_mobile)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Alternative Mobile No.</span>
                        <p class="my-1">{{ $waiver->debt->business->alt_mobile }}</p>
                    </div>
                @endif
                @if ($waiver->debt->business->email_address)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Email Address</span>
                        <p class="my-1">{{ $waiver->debt->business->email }}</p>
                    </div>
                @endif
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Place of Business</span>
                    <p class="my-1">{{ $waiver->debt->business->place_of_business }}</p>
                </div>
            </div>

            @if ($waiver)
            <div class="mx-4">
            <table class="table table-bordered table-striped table-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="text-left font-weight-bold text-uppercase">Waiver Breakdown</label>
                </div>
                <thead>
                    <th style="width: 20%"></th>
                    <th class="text-uppercase" style="width: 30%">Pre-waived Figure (Debt Figure)</th>
                    <th class="text-uppercase" style="width: 30%">Waived Percentage</th>
                    <th class="text-uppercase" style="width: 30%">Post-waived Figure</th>
                </thead>
                <tbody>
                    <tr>
                        <th>Principal Amount</th>
                        <td>{{ $waiver->debt->currency }}. {{ number_format($waiver->debt->return->principal, 2) }}</td>
                        <td>-</td>
                        <td>{{ $waiver->debt->currency }}. {{ number_format($waiver->debt->principal, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Penalty Amount</th>
                        <td>{{ $waiver->debt->currency }}. {{ number_format($waiver->debt->return->penalty, 2) }}</td>
                        <td>{{ number_format($waiver->penalty_rate, 2) }} % = {{ number_format($waiver->debt->return->penalty * ($waiver->penalty_rate/100), 2) }}</td>
                        <td>{{ $waiver->debt->currency }}. {{ number_format($waiver->debt->penalty, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Interest Amount</th>
                        <td>{{ $waiver->debt->currency }}. {{ number_format($waiver->debt->return->interest, 2) }}</td>
                        <td>{{ number_format($waiver->interest_rate, 2) }} % = {{ number_format($waiver->debt->return->interest * ($waiver->interest_rate/100), 2) }}</td>
                        <td>{{ $waiver->debt->currency }}. {{ number_format($waiver->debt->interest, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <td>{{ $waiver->debt->currency }}. {{ number_format($waiver->debt->return->total_amount_due_with_penalties, 2) ?? '' }}</td>
                        <td></td>
                        <td>{{ $waiver->debt->currency }}. {{ number_format($waiver->debt->total_amount, 2) }}</td>
                    </tr>

                </tbody>
            </table>
            </div>
            @endif

            <livewire:approval.return-debt-waiver-approval-processing modelName='App\Models\Debts\DebtWaiver'
            modelId="{{ encrypt($waiver->id) }}" />
        </div>

        <div class="tab-pane fade" id="ground" role="tabpanel" aria-labelledby="ground-tab">
            <div class="row m-2 pt-3">
                <div class="col-md-12 mb-3">
                    <span class="font-weight-bold text-uppercase">Grounds for waiver</span>
                    <p class="my-1">{{ $waiver->ground }}</p>
                </div>
                <div class="col-md-12 mb-3">
                    <span class="font-weight-bold text-uppercase">Reason for waiver</span>
                    <p class="my-1">{{ $waiver->reason }}</p>
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
                                href="{{ route('debts.return.file', encrypt($file->id)) }}"
                                style="font-weight: 500;" class="ml-1">
                                {{ $file['file_name'] }}
                                <i class="bi bi-arrow-up-right-square ml-1"></i>
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <div class="tab-pane m-2 fade" id="approval" role="tabpanel" aria-labelledby="approval-tab">
            <livewire:approval.approval-history-table modelName='App\Models\Debts\DebtWaiver'
            modelId="{{ encrypt($waiver->id) }}" />
        </div>
    </div>

</div>
