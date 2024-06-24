@extends('layouts.master')

@section('title', 'Installment Requests Details')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Installment Request Details
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#request-details" class="nav-item nav-link font-weight-bold active">Request Details</a>
                <a href="#debt-details" class="nav-item nav-link font-weight-bold">{{ get_class($installable) == \App\Models\Returns\TaxReturn::class ? 'Debt' : 'Assessment' }} Details</a>
                <a href="#approval-history" class="nav-item nav-link font-weight-bold">Approval History</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2 border">
                <div id="request-details" class="tab-pane fade active show p-4">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <span class="font-weight-bold text-uppercase">Reasons for the application of payment by installment for the debt.</span>
                            <p class="my-1">{{ $installment->reasons }}</p>
                        </div>
                        <div class="col-md-12 mb-4">
                            <span class="font-weight-bold text-uppercase">Statement of facts in support of the reasons for the application of payment by installment for the debt</span>
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
                        @if($installment->files->count())
                            <div class="col-md-12 mb-2">
                                <span class="font-weight-bold text-uppercase">Attachments</span>
                            </div>
                            @foreach($installment->files as $file)
                                <div class="col-md-3 mb-3">
                                    <a class="file-item" target="_blank"
                                       href="{{ route('installment.requests.file', encrypt($file->location)) }}">
                                        <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                                        <div class="ml-1 font-weight-bold">
                                            <span class="font-weight-bold text-uppercase">{{ $file->name }}</span>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div id="debt-details" class="tab-pane fade p-4">
                    @if(get_class($installable) == \App\Models\Returns\TaxReturn::class)
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Status</span>
                                <p class="my-1">{{ $installable->application_step }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Tax Type</span>
                                <p class="my-1">{{ $installable->taxType->name }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Due Date</span>
                                <p class="my-1">{{ $installable->curr_payment_due_date->toFormattedDateString() }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Principal Amount</span>
                                <p class="my-1">{{ $installable->currency }}. {{ number_format($installable->principal, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Penalty</span>
                                <p class="my-1">{{ $installable->currency }}. {{ number_format($installable->penalty, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Interest</span>
                                <p class="my-1">{{ $installable->currency }}. {{ number_format($installable->interest, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
                                <p class="my-1">{{ $installable->currency }}. {{ number_format($installable->outstanding_amount, 2) }}</p>
                            </div>
                        </div>
                    @endif

                    @if(get_class($installable) == \App\Models\TaxAssessments\TaxAssessment::class)
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Business Name</span>
                                <p class="my-1">{{ $installable->business->name }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">ZIN</span>
                                <p class="my-1">{{ $installable->location->zin }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Mobile</span>
                                <p class="my-1">{{ $installable->business->mobile }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Email</span>
                                <p class="my-1">{{ $installable->business->email }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Tax Type</span>
                                <p class="my-1">{{ $installable->taxType->name }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Payment Due Date</span>
                                <p class="my-1">{{ $installable->curr_payment_due_date->toFormattedDateString() }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Principal Amount</span>
                                <p class="my-1">{{ $installable->currency }}. {{ number_format($installable->principal, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Penalty</span>
                                <p class="my-1">{{ $installable->currency }}. {{ number_format($installable->penalty, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Interest</span>
                                <p class="my-1">{{ $installable->currency }}. {{ number_format($installable->interest, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
                                <p class="my-1">{{ $installable->currency }}. {{ number_format($installable->outstanding_amount, 2) }}</p>
                            </div>
                            </div>
                    @endif
                </div>
                <div id="approval-history" class="tab-pane fade p-4">
                    <livewire:approval.approval-history-table modelName='App\Models\Installment\InstallmentRequest' modelId="{{ encrypt($installment->id) }}" />
                </div>
            </div>
        </div>
    </div>

    <livewire:approval.installment-request-approval-processing modelName="{{ get_class($installment) }}" modelId="{{ encrypt($installment->id) }}" />

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
