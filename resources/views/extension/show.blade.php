@extends('layouts.master')

@section('title', 'Extension Details')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            {{ get_class($extensible) == \App\Models\Returns\TaxReturn::class ? 'Debt' : 'Assessment' }} Details
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#request-details" class="nav-item nav-link font-weight-bold active">Request Details</a>
                <a href="#debt-details" class="nav-item nav-link font-weight-bold">{{ get_class($extensible) == \App\Models\Returns\TaxReturn::class ? 'Debt' : 'Assessment' }} Details</a>
                <a href="#approval-history" class="nav-item nav-link font-weight-bold">Approval History</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2 border">
                <div id="request-details" class="tab-pane fade active show p-4">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <span class="font-weight-bold text-uppercase">Reasons for the application of payment extension of time for the debt.</span>
                            <p class="my-1">{{ $extension->reasons }}</p>
                        </div>
                        <div class="col-md-12 mb-4">
                            <span class="font-weight-bold text-uppercase">Statement of facts in support of the reasons for the application payment extension of time for the debt</span>
                            <p class="my-1">{{ $extension->ground }}</p>
                        </div>
                        @if($extension->extend_from)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Request to Extend From</span>
                                <p class="my-1 text-uppercase">{{ $extension->extend_from->toFormattedDateString() }}</p>
                            </div>
                        @endif
                        @if($extension->extend_to)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase"> To</span>
                                <p class="my-1 text-uppercase">{{ $extension->extend_to->toFormattedDateString() }}</p>
                            </div>
                        @endif
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Status</span>
                            <p class="my-1 text-uppercase">{{ $extension->status }}</p>
                        </div>
                        @if($extension->files->count())
                            <div class="col-md-12 mb-2">
                                <span class="font-weight-bold text-uppercase">Attachments</span>
                            </div>
                            @foreach($extension->files as $file)
                                <div class="col-md-3 mb-3">
                                    <a class="file-item" target="_blank"
                                       href="{{ route('extension.file', encrypt($file->location)) }}">
                                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                        <div style="font-weight: 500;" class="ml-1">
                                            <span class="font-weight-bold text-uppercase">{{ $file->name }}</span>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div id="debt-details" class="tab-pane fade p-4">
                    @if(get_class($extensible) == \App\Models\Returns\TaxReturn::class)
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Status</span>
                                <p class="my-1">{{ $extensible->application_step }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Tax Type</span>
                                <p class="my-1">{{ $extensible->taxType->name }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Due Date</span>
                                <p class="my-1">{{ $extensible->curr_payment_due_date->toFormattedDateString() }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Principal Amount</span>
                                <p class="my-1">{{ $extensible->currency }}. {{ number_format($extensible->principal, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Penalty</span>
                                <p class="my-1">{{ $extensible->currency }}. {{ number_format($extensible->penalty, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Interest</span>
                                <p class="my-1">{{ $extensible->currency }}. {{ number_format($extensible->interest, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
                                <p class="my-1">{{ $extensible->currency }}. {{ number_format($extensible->outstanding_amount, 2) }}</p>
                            </div>
                        </div>
                    @endif

                    @if(get_class($extensible) == \App\Models\TaxAssessments\TaxAssessment::class)
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Business Name</span>
                                <p class="my-1">{{ $extensible->business->name }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">ZIN</span>
                                <p class="my-1">{{ $extensible->location->zin }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Mobile</span>
                                <p class="my-1">{{ $extensible->business->mobile }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Email</span>
                                <p class="my-1">{{ $extensible->business->email }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Tax Type</span>
                                <p class="my-1">{{ $extensible->taxType->name }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Payment Due Date</span>
                                <p class="my-1">{{ $extensible->curr_payment_due_date->toFormattedDateString() }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Principal Amount</span>
                                <p class="my-1">{{ $extensible->currency }}. {{ number_format($extensible->principal, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Penalty</span>
                                <p class="my-1">{{ $extensible->currency }}. {{ number_format($extensible->penalty, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Interest</span>
                                <p class="my-1">{{ $extensible->currency }}. {{ number_format($extensible->interest, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
                                <p class="my-1">{{ $extensible->currency }}. {{ number_format($extensible->outstanding_amount, 2) }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div id="approval-history" class="tab-pane fade p-4">
                    <livewire:approval.approval-history-table modelName='App\Models\Extension\ExtensionRequest' modelId="{{ $extension->id }}" />
                </div>
            </div>
        </div>
    </div>

    <livewire:approval.extension-request-approval-processing modelName="{{ get_class($extension) }}" modelId="{{ $extension->id }}" />

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