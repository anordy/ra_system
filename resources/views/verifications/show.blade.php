@extends('layouts.master')
@section('title', 'Verification Preview')
@section('content')
    <ul class="nav nav-tabs" id="myTab">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
               aria-selected="true">Verification Report</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
               aria-selected="false">Return Information</a>
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
                    Tax Returns Verified
                </div>
                <div class="card-body p-3">
                    <div class="row pt-3">
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Tax Type</span>
                            <p class="my-1">{{ $return->taxtype->name }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Filed By</span>
                            <p class="my-1">{{ $return->taxpayer->full_name ?? '' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Phone Numbers</span>
                            <p class="my-1">{{ $return->taxpayer->mobile ?? '' }}
                                {{ $return->taxpayer->alt_mobile ? '/ ' . $return->taxpayer->alt_mobile : '' }} </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Email</span>
                            <p class="my-1">{{ $return->taxpayer->email ?? '' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Financial Year</span>
                            <p class="my-1">{{ $return->financialYear->name ?? '' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Return Month</span>
                            <p class="my-1">{{ $verification->taxReturn->financialMonth->name ?? '' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Name</span>
                            <p class="my-1">{{ $return->business->name }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Location</span>
                            <p class="my-1">{{ $return->branch->name ?? 'Head Quarter' }}</p>
                        </div>

                        @if (count($riskIndicators ?? []))
                            <div class="col-md-12 mb-3">
                                <span class="font-weight-bold text-uppercase text-danger">Risk Indicators on this return</span>
                                @foreach ($riskIndicators as $riskIndicator)
                                    <ul>
                                        <li><p class="my-1">{{ $riskIndicator->risk_indicator }}</p></li>
                                    </ul>
                                @endforeach
                            </div>
                        @else
                            <div class="col-md-12 mb-3">
                                <span class="font-weight-bold text-uppercase text-danger">No Risk Indicators on this return</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header text-uppercase font-weight-bold bg-white">
                    Verification Details
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($verification->officers as $officer)
                            <div class="col-md-6 mb-3">
                                <span class="font-weight-bold text-uppercase">Team
                                    {{ $officer->team_leader ? 'Leader' : 'Member' }}</span>
                                <p class="my-1">{{ $officer->user->full_name ?? '' }}</p>
                            </div>
                        @endforeach
                        @if ($verification->assessment_report)
                            <div class="col-md-3">
                                <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                                     class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                    <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                    <a target="_blank"
                                       href="{{ route('tax_verifications.files.show', encrypt($verification->assessment_report)) }}"
                                       style="font-weight: 500;" class="ml-1">
                                        Verification Report
                                        <i class="bi bi-arrow-up-right-square ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @if ($verification->assessment)
                <div class="card">
                    <div class="card-header text-uppercase font-weight-bold bg-white">
                        Assessment Details
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Principal Amount</span>
                                <p class="my-1">{{ $verification->assessment->currency ?? 'N/A' }} {{ number_format($verification->assessment->principal_amount ?? 0, 2) }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Penalty Amount</span>
                                <p class="my-1">{{ $verification->assessment->currency ?? 'N/A' }} {{ number_format($verification->assessment->penalty_amount ?? 0, 2) }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Interest Amount</span>
                                <p class="my-1">{{ $verification->assessment->currency ?? 'N/A' }} {{ number_format($verification->assessment->interest_amount ?? 0, 2) }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase"> Total Amount Due</span>
                                <p class="my-1">{{ $verification->assessment->currency ?? 'N/A' }} {{ number_format($verification->assessment->total_amount ?? 0, 2) }}</p>
                            </div>
                            @if (isset($verification->assessment->report_path))
                                <div class="col-md-4">
                                    <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                                         class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                        <a target="_blank"
                                           href="{{ route('tax_verifications.files.show', encrypt($verification->assessment->report_path)) }}"
                                           style="font-weight: 500;" class="ml-1">
                                            Verification Report
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
            @if (view()->exists($viewRender))
                @php echo view($viewRender, compact('return'))->render() @endphp
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Error!</h4>
                            <p>
                                Configured page not found kindly check with Administrator
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="tab-pane fade card p-2" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="card">
                <div class="card-body">
                    <livewire:approval.approval-history-table modelName='{{ get_class($verification) }}'
                                                              modelId="{{ encrypt($verification->id) }}" />
                </div>
            </div>
        </div>
    </div>
@endsection
