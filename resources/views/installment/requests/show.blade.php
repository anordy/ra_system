@extends('layouts.master')

@section('title', 'Installment Requests Details')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Debt Details
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#request-details" class="nav-item nav-link font-weight-bold active">Request Details</a>
                <a href="#debt-details" class="nav-item nav-link font-weight-bold">Debt Details</a>
                <a href="#approval-history" class="nav-item nav-link font-weight-bold">Approval History</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2 border">
                <div id="request-details" class="tab-pane fade active show p-4">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <span class="font-weight-bold text-uppercase">Reasons for the application for extension of time to lodge objection</span>
                            <p class="my-1">{{ $installment->reasons }}</p>
                        </div>
                        <div class="col-md-12 mb-4">
                            <span class="font-weight-bold text-uppercase">Statement of facts in support of the reasons for the application for the extension of time to lodge an objection</span>
                            <p class="my-1">{{ $installment->ground }}</p>
                        </div>
                        @if($installment->installment_from)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Request to pay with installment from</span>
                                <p class="my-1 text-uppercase">{{ $installment->installment_from->toFormattedDateString() }}</p>
                            </div>
                        @endif
                        @if($installment->installment_to)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase"> To</span>
                                <p class="my-1 text-uppercase">{{ $installment->installment_to->toFormattedDateString() }}</p>
                            </div>
                        @endif
                        @if($installment->installment_count)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Installment Phases (Months)</span>
                                <p class="my-1 text-uppercase">{{ $installment->installment_count }} </p>
                            </div>
                        @endif
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Status</span>
                            <p class="my-1 text-uppercase">{{ $installment->status }}</p>
                        </div>
                        @if($installment->attachment)
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
                    </div>
                </div>
                <div id="debt-details" class="tab-pane fade p-4">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Status</span>
                            <p class="my-1">{{ $debt->app_step }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Tax Type</span>
                            <p class="my-1">{{ $debt->taxType->name }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Due Date</span>
                            <p class="my-1">{{ $debt->last_due_date }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Principal Amount</span>
                            <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->principal_amount, 2) }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Penalty</span>
                            <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->penalty, 2) }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Interest</span>
                            <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->interest, 2) }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
                            <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->outstanding_amount, 2) }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Payment Status</span>
                            {{--                    <p class="my-1">{{ $debt->debt->status }}</p>--}}
                        </div>
                    </div>
                </div>
                <div id="approval-history" class="tab-pane fade p-4">
                    <livewire:approval.approval-history-table modelName='App\Models\Installment\InstallmentRequest' modelId="{{ $installment->id }}" />
                </div>
            </div>
        </div>
    </div>

    <livewire:approval.installment-request-approval-processing modelName="{{ get_class($installment) }}" modelId="{{ $installment->id }}" />

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
