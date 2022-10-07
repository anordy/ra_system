<div class="card-body">
    <nav class="nav nav-tabs mt-0 border-top-0">
        <a href="#tab1" class="nav-item nav-link font-weight-bold active">De-registration Details</a>
        @if ($audit = $deregister->audit)
            <a href="#tab2" class="nav-item nav-link font-weight-bold">Audit Details</a>
        @endif
        <a href="#tab3" class="nav-item nav-link font-weight-bold">Approval History</a>
    </nav>
    <div class="tab-content px-2 card pt-3 pb-2">
        <div id="tab1" class="tab-pane fade active show">
            <div class="card-body pb-0">
                <div class="row my-2">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Name</span>
                        <p class="my-1">{{ $deregister->business->name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">TIN</span>
                        <p class="my-1">{{ $deregister->business->tin }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Deregisration Type</span>
                        <p class="my-1">
                            @if ($deregister->deregistration_type === 'all')
                                All Locations
                            @else
                                Single Location
                            @endif
                        </p>
                    </div>
                    @if ($deregister->location_id)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Location</span>
                            <p class="my-1">{{ $deregister->location->name }}</p>
                        </div>
                    @endif
                    @if ($deregister->new_headquarter_id ?? null)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">New Head Quarters</span>
                            <p class="my-1">{{ $deregister->headquarters->name ?? '' }}</p>
                        </div>
                    @endif
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Submitted By</span>
                        <p class="my-1">{{ $deregister->taxpayer->fullname }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">De-registration Date</span>
                        <p class="my-1">{{ $deregister->deregistration_date }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">De-registration Status</span>
                        <p class="my-1">{{ $deregister->status }}</p>
                    </div>
                    @if ($deregister->audit)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Tax Audit Status</span>
                        <p class="my-1">{{ $deregister->audit->status }}</p>
                    </div>
                    @endif
                    <div class="col-md-12 mb-3">
                        <span class="font-weight-bold text-uppercase">Reason for De-registration</span>
                        <p class="my-1">{{ $deregister->reason }}</p>
                    </div>
                </div>

                @if ($deregister->deregistration_type === 'all')
                    @livewire('business.deregister.tax-liability', [
                        'business_id' => $deregister->business_id,
                        'location_id' => null,
                        'deregister_id' => $deregister->id,
                    ])
                @else
                    @livewire('business.deregister.tax-liability', [
                        'business_id' => null,
                        'location_id' => $deregister->location_id,
                        'deregister_id' => $deregister->id,
                    ])
                @endif

            </div>

            @livewire('business.deregister.deregister-approval-processing', ['modelName' => 'App\Models\BusinessDeregistration', 'modelId' => $deregister->id])
        </div>
        <div id="tab2" class="tab-pane fade m-2">
            @if ($audit = $deregister->audit)
                <div>
                    <div class="card-body">
                        <div class="row m-2">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">TIN</span>
                                <p class="my-1">{{ $audit->business->tin ?? '' }}</p>
                            </div>
                            <div class="col-md-8 mb-3">
                                <span class="font-weight-bold text-uppercase">Tax Type</span>
                                <p class="my-1">{{ $audit->taxAuditTaxTypeNames() ?? '' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Business Name</span>
                                <p class="my-1">{{ $audit->business->name ?? '' }}</p>
                            </div>
                            <div class="col-md-8 mb-3">
                                <span class="font-weight-bold text-uppercase">Business Location</span>
                                <p class="my-1">{{ $audit->taxAuditLocationNames() ?? 'Head Quarter' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Auditing From</span>
                                <p class="my-1">{{ $audit->period_from ?? '' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Auditing To</span>
                                <p class="my-1">{{ $audit->period_to ?? '' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Audit Date</span>
                                <p class="my-1">{{ $audit->auditing_date ?? '' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Scope</span>
                                <p class="my-1">{{ $audit->scope ?? '' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Intension</span>
                                <p class="my-1">{{ $audit->intension ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header text-uppercase font-weight-bold bg-white">
                        Audit Findings
                    </div>
                    <div class="card-body">
                        <div class="row">
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
                                            Auditing Working Paper
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
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">Principal Amount</span>
                                    <p class="my-1">
                                        {{ number_format($audit->assessment->principal_amount ?? 0, 2) }}
                                    </p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">Interest Amount</span>
                                    <p class="my-1">{{ number_format($audit->assessment->interest_amount ?? 0, 2) }}
                                    </p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">Penalty Amount</span>
                                    <p class="my-1">{{ number_format($audit->assessment->penalty_amount ?? 0, 2) }}
                                    </p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">Total Amount Due</span>
                                    <p class="my-1">{{ number_format($audit->assessment->total_amount ?? 0, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
        <div id="tab3" class="tab-pane fade m-2">
            <livewire:approval.approval-history-table modelName='App\Models\BusinessDeregistration'
                modelId="{{ $deregister->id }}" />
        </div>
    </div>
</div>


@section('scripts')
    <script>
        $(document).ready(function() {
            $(".nav-tabs a").click(function() {
                $(this).tab('show');
            });
        });
    </script>
@endsection
