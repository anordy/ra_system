@extends("layouts.master")

@section("title", "Investigation Preview")

@section("content")
    @if ($investigation->status == App\Enum\TaxInvestigationStatus::APPROVED && $investigation->assessment)
        <div class="row m-2 pt-3">
            <div class="col-md-12">
                <livewire:assesments.tax-assessment-payment :assessment="$investigation->assessment" />
            </div>
        </div>
    @endif
    <ul class="nav nav-tabs" id="myTab">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                aria-selected="true">Investigation Report</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                aria-selected="false">Returns Information</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact"
                aria-selected="false">Approval Details</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active card p-2" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="card mt-2">
                <div class="card-header text-uppercase font-weight-bold bg-white">
                    INVESTIGATION DETAILS
                </div>
                <div class="card-body">
                    <div class="row m-2">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Case Number</span>
                            <p class="my-1">{{ $investigation->case_number ?? "" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">TIN</span>
                            <p class="my-1">{{ $investigation->business->tin ?? "" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Tax Type</span>
                            <p class="my-1">{{ $investigation->taxInvestigationTaxTypeNames() ?? "" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Name</span>
                            <p class="my-1">{{ $investigation->business->name ?? "" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Location</span>
                            <p class="my-1">{{ $investigation->taxInvestigationLocationNames() ?? "Head Quarter" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Investigation From</span>
                            <p class="my-1">{{ $investigation->period_from ?? "" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Investigation To</span>
                            <p class="my-1">{{ $investigation->period_to ?? "" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Allegations</span>
                            <p class="my-1">{{ $investigation->intension ?? "" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Descriptions</span>
                            <p class="my-1">{{ $investigation->scope ?? "" }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($investigation->officers->count() > 0)
                <div class="card">
                    <div class="card-header text-uppercase font-weight-bold bg-white">
                        <div class="row">
                            <div class="col">Investigation Details</div>
                            <div class="col-md-2">
                                <button class="btn btn-warning"
                                    onclick="Livewire.emit('showModal', 'investigation.edit-investigation-members-modal', '{{ encrypt($investigation) }}')">
                                    <i class="bi bi-pencil"></i> Edit members
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($investigation->officers as $officer)
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">Team{{ $officer->team_leader ? "Leader" : "Member" }}</span>
                                    <p class="my-1">{{ $officer->user->full_name ?? "" }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            @if ($investigation->notice_of_discussion)
                                <div class="col-md-4">
                                    <div
                                        class="p-2 mb-3 d-flex rounded-sm align-items-center highlighted-file-box">
                                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                        <a target="_blank"
                                            href="{{ route("tax_investigation.files.show", encrypt($investigation->notice_of_discussion)) }}"
                                            style="font-weight: 500;" class="ml-1">
                                            Notice of Discussion / Interview
                                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if ($investigation->preliminary_report)
                                <div class="col-md-4">
                                    <div
                                        class="p-2 mb-3 d-flex rounded-sm align-items-center highlighted-file-box">
                                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                        <a target="_blank"
                                            href="{{ route("tax_investigation.files.show", encrypt($investigation->preliminary_report)) }}"
                                            style="font-weight: 500;" class="ml-1">
                                            Preliminary Report
                                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if ($investigation->final_report)
                                <div class="col-md-4">
                                    <div
                                        class="p-2 mb-3 d-flex rounded-sm align-items-center highlighted-file-box">
                                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                        <a target="_blank"
                                            href="{{ route("tax_investigation.files.show", encrypt($investigation->final_report)) }}"
                                            style="font-weight: 500;" class="ml-1">
                                            Final Report
                                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if ($investigation->working_report)
                                <div class="col-md-4">
                                    <div
                                        class="p-2 mb-3 d-flex rounded-sm align-items-center highlighted-file-box">
                                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                        <a target="_blank"
                                            href="{{ route("tax_investigation.files.show", encrypt($investigation->working_report)) }}"
                                            style="font-weight: 500;" class="ml-1">
                                            Working Report
                                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header text-uppercase font-weight-bold bg-white">
                    Approval comments
                </div>
                <div class="card-body">
                    <livewire:approval.approval-history-table modelName='{{ get_class($investigation) }}'
                        modelId="{{ encrypt($investigation->id) }}" isSummary="{{ true }}" />
                </div>
            </div>

            @if ($investigation->assessment)
                <div class="card">
                    <div class="card-header text-uppercase font-weight-bold bg-white">
                        Assessment Details
                        <div class="card-tools">
                            <a class="btn btn-primary" target="_blank" href="{{ route('tax_investigation.assessments.notice', encrypt($investigation->id)) }}">
                                <i class="bi bi-file-earmark-pdf-fill mr-1"></i>
                                Get Notice of Assessment
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                            $grandTotal = 0;
                            $outstandingTotal = 0;
                        @endphp

                        @foreach ($taxAssessments as $taxAssessment)
                            <div>
                                <h6>{{ $taxAssessment->taxtype->name }} Assesment :</h6>
                                <div class="row">
                                    <div class="col-md-2 mb-3">
                                        <span class="font-weight-bold text-uppercase">Principal Amount</span>
                                        <p class="my-1">{{ number_format($taxAssessment->principal_amount ?? 0, 2) }}</p>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <span class="font-weight-bold text-uppercase">Interest Amount</span>
                                        <p class="my-1">{{ number_format($taxAssessment->interest_amount ?? 0, 2) }}</p>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <span class="font-weight-bold text-uppercase">Penalty Amount</span>
                                        <p class="my-1">{{ number_format($taxAssessment->penalty_amount ?? 0, 2) }}</p>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <span class="font-weight-bold text-uppercase">Total Amount Due</span>
                                        <p class="my-1">{{ number_format($taxAssessment->total_amount ?? 0, 2) }}</p>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
                                        <p class="my-1">{{ number_format($taxAssessment->outstanding_amount ?? 0, 2) }}</p>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <span class="font-weight-bold text-uppercase">Payment Status</span>
                                        <p class="my-1">
                                            @if ($taxAssessment->outstanding_amount === 0 || $taxAssessment->outstanding_amount === "0")
                                                <span class="badge badge-success">PAID</span>
                                            @else
                                                <span class="badge badge-warning">PENDING </span>
                                            @endif
                                        </p>
                                    </div>

                                </div>
                                @php
                                    $grandTotal += $taxAssessment->total_amount;
                                    $outstandingTotal += $taxAssessment->outstanding_amount;
                                @endphp
                            </div>
                        @endforeach

                        <div class="row justify-content-end">
                            <div class="col-md-2 mb-3">
                                <span class="font-weight-bold text-uppercase">Grand Total Amount</span>
                                <p class="my-1">{{ number_format($grandTotal, 2) }}</p>
                            </div>
                            <div class="col-md-2 mb-3">
                                <span class="font-weight-bold text-uppercase">Total Outstanding Amount</span>
                                <p class="my-1">{{ number_format($outstandingTotal, 2) }}</p>
                            </div>
                            <div class="col-md-2 mb-3">

                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <livewire:approval.tax-investigation-approval-processing modelName='{{ get_class($investigation) }}'
                modelId="{{ encrypt($investigation->id) }}" />

        </div>
        <div class="tab-pane fade card p-2" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            @if ($investigation->location_id != 0 && $investigation->tax_type_id != 0)
                @livewire("investigation.declared-sales-analysis", ["investigationId" => encrypt($investigation->id), "tax_type_id" => encrypt($investigation->tax_type_id), "location_id" => encrypt($investigation->location_id)])
                @livewire("investigation.declared-sales-analysis", ["investigationId" => encrypt($investigation->id), "tax_type_id" => encrypt($investigation->tax_type_id), "location_id" => encrypt($investigation->location_id)])
            @else
                @livewire("investigation.declared-sales-analysis-instances", ["investigationId" => encrypt($investigation->id)])
                @livewire("investigation.declared-sales-analysis-instances", ["investigationId" => encrypt($investigation->id)])
            @endif
        </div>
        <div class="tab-pane fade card p-2" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="card">
                <div class="card-body">
                    <livewire:approval.approval-history-table modelName='{{ get_class($investigation) }}'
                        modelId="{{ encrypt($investigation->id) }}" />
                </div>
            </div>
        </div>
    </div>

@endsection
