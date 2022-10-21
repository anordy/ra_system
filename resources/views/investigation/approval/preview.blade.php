@extends('layouts.master')

@section('title', 'Investigation Preview')

@section('content')
    <ul class="nav nav-tabs" id="myTab" role="tablist">
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
                    TAXPAYER INFORMATIONS
                </div>
                <div class="card-body">
                    <div class="row m-2">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">TIN</span>
                            <p class="my-1">{{ $investigation->business->tin ?? '' }}</p>
                        </div>
                        <div class="col-md-8 mb-3">
                            <span class="font-weight-bold text-uppercase">Tax Type</span>
                            <p class="my-1">{{ $investigation->taxInvestigationTaxTypeNames() ?? '' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Name</span>
                            <p class="my-1">{{ $investigation->business->name ?? '' }}</p>
                        </div>
                        <div class="col-md-8 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Location</span>
                            <p class="my-1">{{ $investigation->taxInvestigationLocationNames() ?? 'Head Quarter' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Investigation From</span>
                            <p class="my-1">{{ $investigation->period_from ?? '' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Investigation To</span>
                            <p class="my-1">{{ $investigation->period_to ?? '' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Scope</span>
                            <p class="my-1">{{ $investigation->scope ?? '' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Intension</span>
                            <p class="my-1">{{ $investigation->intension ?? '' }}</p>
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
                                        {{ $officer->team_leader ? 'Leader' : 'Member' }}</span>
                                    <p class="my-1">{{ $officer->user->full_name ?? '' }}</p>
                                </div>
                            @endforeach
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
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Principal Amount</span>
                                <p class="my-1">{{ number_format($investigation->assessment->principal_amount ?? 0, 2) }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Penalty Amount</span>
                                <p class="my-1">{{ number_format($investigation->assessment->penalty_amount ?? 0, 2) }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Interest Amount</span>
                                <p class="my-1">{{ number_format($investigation->assessment->interest_amount ?? 0, 2) }}
                                </p>
                            </div>

                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Total Amount Due</span>
                                <p class="my-1">{{ number_format($investigation->assessment->total_amount ?? 0, 2) }}</p>
                            </div>
                            @if ($investigation->investigation_report)
                                <div class="col-md-4">
                                    <div style="background: #faf5f5; color: #863d3c; border: .5px solid #863d3c24;"
                                        class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                        <a target="_blank"
                                            href="{{ route('tax_investigation.files.show', encrypt($investigation->investigation_report)) }}"
                                            style="font-weight: 500;" class="ml-1">
                                            Investigation Report
                                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if ($investigation->working_report)
                                <div class="col-md-4">
                                    <div style="background: #faf5f5; color: #863d3c; border: .5px solid #863d3c24;"
                                        class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                        <a target="_blank"
                                            href="{{ route('tax_investigation.files.show', encrypt($investigation->working_report)) }}"
                                            style="font-weight: 500;" class="ml-1">
                                            Auditing Working Paper
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
        <div class="tab-pane fade card p-2" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            @if ($investigation->location_id != 0 && $investigation->tax_type_id != 0)
                @livewire('investigation.declared-sales-analysis', ['investigation' => $investigation, 'tax_type_id' => $investigation->tax_type_id, 'location_id' => $investigation->location_id])
            @else
                @livewire('investigation.declared-sales-analysis-instances', ['investigation' => $investigation])
            @endif
        </div>
        <div class="tab-pane fade card p-2" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="card">
                <div class="card-body">
                    <livewire:approval.approval-history-table modelName='{{ get_class($investigation) }}'
                        modelId="{{ $investigation->id }}" />

                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $(".nav-tabs a").click(function() {
                $(this).tab('show');
            });
        });
    </script>
@endsection
