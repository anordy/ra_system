@extends('layouts.master')

@section('title', 'Installment Requests Details')

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
    </div>

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Installment Request Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <span class="font-weight-bold text-uppercase">Reasons for the application for extension of time to lodge objection</span>
                    <p class="my-1">{{ $installment->reasons }}</p>
                </div>
                <div class="col-md-12 mb-4">
                    <span class="font-weight-bold text-uppercase">Statement of facts in support of the reasons for the application for the extension of time to lodge an objection</span>
                    <p class="my-1">{{ $installment->ground }}</p>
                </div>
                @if($installment->status === \App\Enum\InstallmentRequestStatus::APPROVED)
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
                @endif
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Status</span>
                    <p class="my-1 text-uppercase">{{ $installment->status }}</p>
                </div>
                @if($installment->attachment)
                    <div class="col-md-4 mb-3">
                        <a class="file-item" target="_blank"
                           href="{{ route('installment.file', encrypt($installment->attachment)) }}">
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

@endsection