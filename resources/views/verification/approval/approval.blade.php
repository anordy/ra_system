@extends('layouts.master')

@section('title', 'Approval Details')

@section('content')
    <ul class="nav nav-tabs" id="myTab" role="tablist">
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
                <div class="card-body">

                    <div class="row m-2 pt-3">
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Tax Type</span>
                            <p class="my-1">{{ $verification->taxtype->name }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Filled By</span>
                            <p class="my-1">{{ $verification->createdBy->full_name ?? '' }}</p>
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
                                <div style="background: #faf5f5; color: #863d3c; border: .5px solid #863d3c24;"
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
                        Adjusted Assessment Details
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Principal Amount</span>
                                <p class="my-1">{{ $verification->assessment->principal_amount ?? '' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Penalty Amount</span>
                                <p class="my-1">{{ $verification->assessment->penalty_amount ?? '' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Interest Amount</span>
                                <p class="my-1">{{ $verification->assessment->interest_amount ?? '' }}</p>
                            </div>

                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Total Amount Due</span>
                                <p class="my-1">{{ $verification->assessment->total_amount ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            <livewire:approval.tax-verification-approval-processing modelName='{{ get_class($verification) }}'
                modelId="{{ $verification->id }}" />
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
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
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="card">
                <div class="card-body">
                    <livewire:approval.approval-history-table modelName='{{ get_class($verification) }}'
                        modelId="{{ $verification->id }}" />

                </div>
            </div>
        </div>
    </div>

@endsection
