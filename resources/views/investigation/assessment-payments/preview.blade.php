@extends("layouts.master")

@section("title", "Investigation Preview")

@section("content")

    {{-- @dd($partialPayment->bill) --}}
    @if ($partialPayment->status == App\Enum\TaxInvestigationStatus::APPROVED && $investigation->assessment)
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
                aria-selected="true">Investigation Report</a>
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
                            <p class="my-1">{{ $investigation->scope ?? "" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Descriptions</span>
                            <p class="my-1">{{ $investigation->intension ?? "" }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($investigation->officers->count() > 0)
                <div class="card">
                    <div class="card-header text-uppercase font-weight-bold bg-white">
                        Investigation Details
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($investigation->officers as $officer)
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">Team
                                        {{ $officer->team_leader ? "Leader" : "Member" }}</span>
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

            @if ($investigation->assessment)
                <div class="card">
                    <div class="card-header text-uppercase font-weight-bold bg-white">
                        Assessment Details
                    </div>
                    <div class="card-body">
                        @php $grandTotal = 0; @endphp

                        @foreach ($taxAssessments as $taxAssessment)
                            <div>
                                <h6>{{ $taxAssessment->taxtype->name }} Assesment :</h6>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <span class="font-weight-bold text-uppercase">Principal Amount</span>
                                        <p class="my-1">{{ number_format($taxAssessment->principal_amount ?? 0, 2) }}</p>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <span class="font-weight-bold text-uppercase">Interest Amount</span>
                                        <p class="my-1">{{ number_format($taxAssessment->interest_amount ?? 0, 2) }}</p>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <span class="font-weight-bold text-uppercase">Penalty Amount</span>
                                        <p class="my-1">{{ number_format($taxAssessment->penalty_amount ?? 0, 2) }}</p>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <span class="font-weight-bold text-uppercase">Total Amount Due</span>
                                        <p class="my-1">{{ number_format($taxAssessment->total_amount ?? 0, 2) }}</p>
                                    </div>
                                </div>
                                @php $grandTotal += $taxAssessment->total_amount; @endphp
                            </div>
                        @endforeach

                        <div class="row justify-content-end">
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Grand Total Amount</span>
                                <p class="my-1">{{ number_format($grandTotal, 2) }}</p>
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
                    <div class="row">
                        <p class="p-3">
                            Tax payer wants to make a payment of
                            <span class="font-weight-bold text-uppercase"> {{ number_format($partialPayment->amount, 2) }} </span>
                            which is equal to
                            <span class="font-weight-bold text-uppercase">
                                {{ number_format(($partialPayment->amount / $partialPayment->taxAssessment->outstanding_amount) * 100, 2) }}%
                            </span>
                            percent of total Outstanding Amount of {{ $partialPayment->taxAssessment->taxtype->name }} Assesment
                        </p>
                    </div>

                </div>

            </div>

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

        </div>
    </div>

@endsection
