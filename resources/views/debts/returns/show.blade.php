@extends('layouts.master')

@section('title', 'View Debt')

@section('content')
    <div class="card">
        <div class="card-body">
            <div>
                <h6 class="text-uppercase mt-2 ml-2">{{ $tax_return->taxtype->name ?? '' }} Debt
                    {{ $tax_return->financialMonth->name ?? '' }},
                    {{ $tax_return->financialMonth->year->code ?? '' }}</h6>
                <hr>
            </div>

            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Debt Details</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Return Details</a>
                <a href="#tab3" class="nav-item nav-link font-weight-bold">Penalties</a>
                @if ($tax_return->debtWaiver)
                    <a href="#tab4" class="nav-item nav-link font-weight-bold">Waiver Details</a>
                @endif
                @if (count($tax_return->demandNotices) > 0)
                    <a href="#tab5" class="nav-item nav-link font-weight-bold">Demand Notices</a>
                @endif
                @if (count($tax_return->recoveryMeasure->measures) > 0)
                <a href="#tab6" class="nav-item nav-link font-weight-bold">Assigned Recovery Measures</a>
                @endif
                @if ($tax_return->recoveryMeasure)
                    <a href="#tab7" class="nav-item nav-link font-weight-bold">Recovery Measure Approval History</a>
                @endif
            </nav>

            <div class="tab-content px-2 card pt-3 pb-2">
                <div id="tab1" class="tab-pane fade active show m-4">
                    @if (count($tax_return->recoveryMeasure->measures) == 0)
                        <div class="card-tools">
                                <a href="{{ route('debts.debt.recovery', encrypt($tax_return->id)) }}"  class="btn btn-info btn-sm text-white" style="color: white !important;"><i
                                    class="fa fa-plus text-white"></i>
                                    Assign Recovery Measure
                                </a>
                        </div>
                    @endif
                    @include('debts.details', ['tax_return' => $tax_return])
                </div>
                <div id="tab2" class="tab-pane fade m-4">
                    @include('debts.return-details', ['tax_return' => $tax_return])
                </div>
                <div id="tab3" class="tab-pane fade m-4">
                    <livewire:debt.debt-penalties :penalties="$tax_return->return->penalties ?? []" />
                </div>
                <div id="tab4" class="tab-pane fade  m-4">
                    @include('debts.waiver-details', ['tax_return' => $tax_return])
                </div>
                <div id="tab5" class="tab-pane fade m-4">
                    <livewire:debt.demand-notice.demand-notice-table debtId="{{ $tax_return->id }}" />
                </div>
                <div id="tab6" class="tab-pane fade m-4">
                    <h6 class="text-uppercase mt-2 ml-2">Recommended Recovery Measures</h6>
                    <hr>
                    <div class="row m-2 pt-3">
    
                        @if (count($tax_return->recoveryMeasure->measures) > 0)
                            @foreach ($tax_return->recoveryMeasure->measures as $key => $recovery_measure)
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Measure Type</span>
                                    <p class="my-1">{{ $key + 1 }}.
                                        {{ $recovery_measure->category->name }}</p>
                                </div>
                            @endforeach
                        @else
                            <p class="my-1">No Assigned Recovery Measures</p>
                        @endif
    
                    </div>
                </div>
                <div id="tab7" class="tab-pane fade m-4">
                    <livewire:approval.approval-history-table modelName='App\Models\Debts\RecoveryMeasure'
                        modelId="{{ $tax_return->id }}" />
                </div>
            </div>
        </div>
    </div>

    @if ($tax_return->extensionRequest || $tax_return->installment)

        <div class="card rounded-0 mt-3">
            <div class="card-header bg-white">
                Payments Extensions / Installment Details
            </div>
            <div class="card-body">
                <div class="row">
                    @if ($extension = $tax_return->extensionRequest)
                        <div class="col-md-12 mb-4">
                            <span class="font-weight-bold text-uppercase">Reasons for the application for extension of time
                                to lodge objection</span>
                            <p class="my-1">{{ $extension->reasons }}</p>
                        </div>
                        <div class="col-md-12 mb-4">
                            <span class="font-weight-bold text-uppercase">Statement of facts in support of the reasons for
                                the application for the extension of time to lodge an objection</span>
                            <p class="my-1">{{ $extension->ground }}</p>
                        </div>
                        @if ($extension->status === \App\Enum\ExtensionRequestStatus::APPROVED)
                            @if ($extension->extend_from)
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Request to Extend From</span>
                                    <p class="my-1 text-uppercase">{{ $extension->extend_from->toFormattedDateString() }}
                                    </p>
                                </div>
                            @endif
                            @if ($extension->extend_to)
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase"> To</span>
                                    <p class="my-1 text-uppercase">{{ $extension->extend_to->toFormattedDateString() }}</p>
                                </div>
                            @endif
                        @endif
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Status</span>
                            <p class="my-1 text-uppercase">{{ $extension->status }}</p>
                        </div>
                        @if ($extension->attachment)
                            <div class="col-md-4 mb-3">
                                <a class="file-item" target="_blank"
                                    href="{{ route('extension.file', encrypt($extension->attachment)) }}">
                                    <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                    <div style="font-weight: 500;" class="ml-1">
                                        <span class="font-weight-bold text-uppercase">Attachment</span>
                                    </div>
                                </a>
                            </div>
                        @endif
                    @endif

                    @if ($installment = $tax_return->installmentRequest)
                        <div class="col-md-12 mb-4">
                            <span class="font-weight-bold text-uppercase">Reasons for the application for extension of time
                                to lodge objection</span>
                            <p class="my-1">{{ $installment->reasons }}</p>
                        </div>
                        <div class="col-md-12 mb-4">
                            <span class="font-weight-bold text-uppercase">Statement of facts in support of the reasons for
                                the application for the extension of time to lodge an objection</span>
                            <p class="my-1">{{ $installment->ground }}</p>
                        </div>
                        @if ($installment->status === \App\Enum\InstallmentRequestStatus::APPROVED)
                            @if ($installment->installment_from)
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Request to pay with installment
                                        from</span>
                                    <p class="my-1 text-uppercase">
                                        {{ $installment->installment_from->toFormattedDateString() }}</p>
                                </div>
                            @endif
                            @if ($installment->installment_to)
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase"> To</span>
                                    <p class="my-1 text-uppercase">
                                        {{ $installment->installment_to->toFormattedDateString() }}</p>
                                </div>
                            @endif
                            @if ($installment->installment_count)
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Installment Phases (Months)</span>
                                    <p class="my-1 text-uppercase">{{ $installment->installment_count }} </p>
                                </div>
                            @endif
                        @endif
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Status</span>
                            <p class="my-1 text-uppercase">{{ $installment->status }}</p>
                        </div>
                        @if ($installment->attachment)
                            <div class="col-md-4 mb-3">
                                <a class="file-item" target="_blank"
                                    href="{{ route('installment.requests.file', encrypt($installment->attachment)) }}">
                                    <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                    <div style="font-weight: 500;" class="ml-1">
                                        <span class="font-weight-bold text-uppercase">Attachment</span>
                                    </div>
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

    @endif

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
