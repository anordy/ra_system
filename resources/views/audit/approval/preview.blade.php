@extends('layouts.master')

@section('title', 'Audit Preview')

@section('content')
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                aria-selected="true">Investigation Report</a>
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
                    TAXPAYER INFORMATIONS
                </div>
                <div class="card-body">
                    <div class="row m-2">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Tax Type</span>
                            <p class="my-1">{{ $return->taxtype->name }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Name</span>
                            <p class="my-1">{{ $return->business->name }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Location</span>
                            <p class="my-1">{{ $return->branch->name ?? 'Head Quarter' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header text-uppercase font-weight-bold bg-white">
                    Audit Informations
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Audit Date</span>
                            <p class="my-1">{{ $audit->auditing_date ?? '' }}</p>
                        </div>
                        @if (count($audit->officers) > 0)
                            @foreach ($audit->officers as $officer)
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Team
                                        {{ $officer->team_leader ? 'Leader' : 'Member' }}</span>
                                    <p class="my-1">{{ $officer->user->full_name ?? '' }}</p>
                                </div>
                            @endforeach
                        @endif

                    </div>
                    <div class="row">
                        @if ($audit->preliminary_report)
                            <div class="col-md-3">
                                <div style="background: #faf5f5; color: #863d3c; border: .5px solid #863d3c24;"
                                    class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                    <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                    <a target="_blank"
                                        href="{{ route('tax_auditing.files.show', encrypt($audit->preliminary_report)) }}"
                                        style="font-weight: 500;" class="ml-1">
                                        Preliminary Report
                                        <i class="bi bi-arrow-up-right-square ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if ($audit->working_report)
                            <div class="col-md-3">
                                <div style="background: #faf5f5; color: #863d3c; border: .5px solid #863d3c24;"
                                    class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                    <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                    <a target="_blank"
                                        href="{{ route('tax_auditing.files.show', encrypt($audit->working_report)) }}"
                                        style="font-weight: 500;" class="ml-1">
                                        Working Report
                                        <i class="bi bi-arrow-up-right-square ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if ($audit->final_report)
                            <div class="col-md-3">
                                <div style="background: #faf5f5; color: #863d3c; border: .5px solid #863d3c24;"
                                    class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                    <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                    <a target="_blank"
                                        href="{{ route('tax_auditing.files.show', encrypt($audit->final_report)) }}"
                                        style="font-weight: 500;" class="ml-1">
                                        Final Report
                                        <i class="bi bi-arrow-up-right-square ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if ($audit->exit_minutes)
                            <div class="col-md-3">
                                <div style="background: #faf5f5; color: #863d3c; border: .5px solid #863d3c24;"
                                    class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                    <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                    <a target="_blank"
                                        href="{{ route('tax_auditing.files.show', encrypt($audit->exit_minutes)) }}"
                                        style="font-weight: 500;" class="ml-1">
                                        Exit Minutes
                                        <i class="bi bi-arrow-up-right-square ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($audit->assessment)
                <div class="card">
                    <div class="card-header text-uppercase font-weight-bold bg-white">
                        Assessment Details
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Principal Amount</span>
                                <p class="my-1">{{ $audit->assessment->principal_amount ?? '' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Interest Amount</span>
                                <p class="my-1">{{ $audit->assessment->interest_amount ?? '' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Penalty Amount</span>
                                <p class="my-1">{{ $audit->assessment->penalty_amount ?? '' }}</p>
                            </div>
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
            <livewire:approval.approval-history-table modelName='{{ get_class($audit) }}'
                modelId="{{ $audit->id }}" />
        </div>
    </div>

@endsection
