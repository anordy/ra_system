@extends('layouts.master')

@section('title', 'Extension Details')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Debt Details
        </div>
        <div class="card-body">
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
                    <p class="my-1">{{ $debt->curr_due_date }}</p>
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
    </div>

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Extension Request Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <span class="font-weight-bold text-uppercase">Reasons for the application for extension of time to lodge objection</span>
                    <p class="my-1">{{ $extension->reasons }}</p>
                </div>
                <div class="col-md-12 mb-4">
                    <span class="font-weight-bold text-uppercase">Statement of facts in support of the reasons for the application for the extension of time to lodge an objection</span>
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
                @if($extension->attachment)
                    <div class="col-md-4 mb-3 mt-3">
                        <span class="font-weight-bold text-uppercase mb-2 d-block">Attachment</span>
                        <a class="file-item"  target="_blank"  href="{{ route('extension.file', encrypt($extension->attachment)) }}">
                            <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                            <div style="font-weight: 500;" class="ml-1">
                                <span class="font-weight-bold text-uppercase">Attachment</span>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <livewire:approval.extension-request-approval-processing modelName="{{ get_class($extension) }}" modelId="{{ $extension->id }}" />

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Approval History
        </div>
        <div class="card-body">
            <livewire:approval.approval-history-table modelName='App\Models\Extension\ExtensionRequest' modelId="{{ $extension->id }}" />
        </div>
    </div>
@endsection