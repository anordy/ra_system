@extends("layouts.master")

@php
    $subjectType = get_class($subject);
    $subjectType = explode("\\", $subjectType);
    $subjectType = end($subjectType);
    $subjectType = preg_replace("/(?<!^)([A-Z])/", ' $1', $subjectType);
@endphp

@section("title", "{{ $subjectType }} Preview")

@section("content")
    @if ($partialPayment->status == App\Enum\TaxInvestigationStatus::APPROVED && $subject->assessment)
        <div class="row m-2 pt-3">
            <div class="col-md-12">
                <livewire:assesments.tax-assessment-payment :assessment="$partialPayment" />
            </div>
        </div>
    @endif
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                aria-selected="false">Assesment Details</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                aria-selected="true">{{ $subjectType }} Report</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade card p-2" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="card mt-2">
                <div class="card-header text-uppercase font-weight-bold bg-white">
                    TAXPAYER INFORMATIONS
                </div>
                <div class="card-body">
                    <div class="row m-2">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Case Number</span>
                            <p class="my-1">{{ $subject->case_number ?? "" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">TIN</span>
                            <p class="my-1">{{ $subject->business->tin ?? "" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Tax Type</span>
                            {{-- <p class="my-1">{{ $subject->taxInvestigationTaxTypeNames() ?? "" }}</p> --}}
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Name</span>
                            <p class="my-1">{{ $subject->business->name ?? "" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Location</span>
                            {{-- <p class="my-1">{{ $subject->taxInvestigationLocationNames() ?? "Head Quarter" }}</p> --}}
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ $subjectType }} From</span>
                            <p class="my-1">{{ $subject->period_from ?? "" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ $subjectType }} To</span>
                            <p class="my-1">{{ $subject->period_to ?? "" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Allegations</span>
                            <p class="my-1">{{ $subject->scope ?? "" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Descriptions</span>
                            <p class="my-1">{{ $subject->intension ?? "" }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($subject->officers->count() > 0)
                <div class="card">
                    <div class="card-header text-uppercase font-weight-bold bg-white">
                        {{ $subjectType }} Details
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($subject->officers as $officer)
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">Team
                                        {{ $officer->team_leader ? "Leader" : "Member" }}</span>
                                    <p class="my-1">{{ $officer->user->full_name ?? "" }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            @if ($subject->notice_of_discussion)
                                <div class="col-md-4">
                                    <div
                                        class="p-2 mb-3 d-flex rounded-sm align-items-center highlighted-file-box">
                                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                        <a target="_blank"
                                            href="{{ route("tax_investigation.files.show", encrypt($subject->notice_of_discussion)) }}"
                                            style="font-weight: 500;" class="ml-1">
                                            Notice of Discussion / Interview
                                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if ($subject->preliminary_report)
                                <div class="col-md-4">
                                    <div
                                        class="p-2 mb-3 d-flex rounded-sm align-items-center highlighted-file-box">
                                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                        <a target="_blank"
                                            href="{{ route("tax_investigation.files.show", encrypt($subject->preliminary_report)) }}"
                                            style="font-weight: 500;" class="ml-1">
                                            Preliminary Report
                                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if ($subject->final_report)
                                <div class="col-md-4">
                                    <div
                                        class="p-2 mb-3 d-flex rounded-sm align-items-center highlighted-file-box">
                                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                        <a target="_blank"
                                            href="{{ route("tax_investigation.files.show", encrypt($subject->final_report)) }}"
                                            style="font-weight: 500;" class="ml-1">
                                            Final Report
                                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if ($subject->working_report)
                                <div class="col-md-4">
                                    <div
                                        class="p-2 mb-3 d-flex rounded-sm align-items-center highlighted-file-box">
                                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                        <a target="_blank"
                                            href="{{ route("tax_investigation.files.show", encrypt($subject->working_report)) }}"
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

            @if ($subject->assessment)
                <div class="card">
                    <div class="card-header text-uppercase font-weight-bold bg-white">
                        Assessment Details
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
                                        <p class="my-1">{{ number_format($taxAssessment->principal_amount ?? 0, 2) }}
                                            {{ $taxAssessment->currency }}</p>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <span class="font-weight-bold text-uppercase">Interest Amount</span>
                                        <p class="my-1">{{ number_format($taxAssessment->interest_amount ?? 0, 2) }} {{ $taxAssessment->currency }}
                                        </p>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <span class="font-weight-bold text-uppercase">Penalty Amount</span>
                                        <p class="my-1">{{ number_format($taxAssessment->penalty_amount ?? 0, 2) }} {{ $taxAssessment->currency }}
                                        </p>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <span class="font-weight-bold text-uppercase">Total Amount Due</span>
                                        <p class="my-1">{{ number_format($taxAssessment->total_amount ?? 0, 2) }} {{ $taxAssessment->currency }}</p>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
                                        <p class="my-1">{{ number_format($taxAssessment->outstanding_amount ?? 0, 2) }}
                                            {{ $taxAssessment->currency }}</p>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <span class="font-weight-bold text-uppercase">Payment Status</span>
                                        <p class="my-1">
                                            @if ($taxAssessment->outstanding_amount === 0 || $taxAssessment->outstanding_amount === "0")
                                                <span class="badge badge-success">PAID</span>
                                            @elseif($taxAssessment->outstanding_amount < $taxAssessment->total_amount)
                                                <span class="badge badge-warning">PAID PARTIALLY </span>
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
                                <p class="my-1">{{ number_format($grandTotal, 2) }} {{ $taxAssessment->currency }}</p>
                            </div>
                            <div class="col-md-2 mb-3">
                                <span class="font-weight-bold text-uppercase">Total Outstanding Amount</span>
                                <p class="my-1">{{ number_format($outstandingTotal, 2) }} {{ $taxAssessment->currency }}</p>
                            </div>
                            <div class="col-md-2 mb-3">

                            </div>
                        </div>
                    </div>

                </div>
            @endif

        </div>

        <div class="tab-pane fade show active  card p-2" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="card">
                <div class="card-header text-uppercase font-weight-bold bg-white">
                    Assessment Payments
                </div>
                <div class="card-body">
                    <div>
                        <h6>{{ $partialPayment->taxAssessment->taxtype->name }} Assesment Payments:</h6> <br>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Original Assesment Amount</span>
                                <p class="my-1">{{ number_format($partialPayment->taxAssessment->original_total_amount ?? 0, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Paid Assesment Amount</span>
                                <p class="my-1">{{ number_format($partialPayment->taxAssessment->paid_amount ?? 0, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Outstanding Assesment Amount</span>
                                <p class="my-1">{{ number_format($partialPayment->taxAssessment->outstanding_amount ?? 0, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    @if ($partialPayment->taxAssessment->outstanding_amount > 0)
                        <div class="row">
                            <p class="p-3">
                                Taxpayer wants to make a payment of
                                <span class="font-weight-bold text-uppercase">{{ number_format($partialPayment->amount, 2) }}</span>
                                which is equal to
                                <span class="font-weight-bold text-uppercase">
                                    @if ($partialPayment->taxAssessment->outstanding_amount != 0)
                                        {{ number_format(max(0, min(($partialPayment->amount / $partialPayment->taxAssessment->outstanding_amount) * 100, 100)), 2) }}%
                                    @else
                                        0
                                    @endif
                                </span>
                                percent of total Outstanding Amount of {{ $partialPayment->taxAssessment->taxtype->name }} Assessment
                            </p>
                        </div>
                    @endif

                </div>

            </div>

            @if ($partialPayment->status === App\Enum\TaxInvestigationStatus::PENDING)
                <form action="{{ route("tax_investigation.approve-reject", encrypt($partialPayment->id)) }}" method="POST">
                    @csrf
                    <div class="row p-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="comments">Comments</label>
                                <textarea class="form-control @error("comments") is-invalid @enderror" name="comments" rows="3" required>{{ old("comments") }}</textarea>
                                @error("comments")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-2 m-0">
                        <button type="submit" name="action" value="reject" class="btn btn-danger">
                            Reject & Return Back
                        </button>
                        <button type="submit" name="action" value="approve" class="btn btn-primary">
                            Approve & Generate Control No
                        </button>
                    </div>
                </form>
            @endif

        </div>
    </div>

@endsection
