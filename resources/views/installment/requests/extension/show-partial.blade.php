@extends('layouts.master')

@section('title', 'View Installment Partial Payment Request')

@section('content')

    <div class="card rounded-0 mt-3">
        <div class="card-header bg-white">
            Partial Details
        </div>
        <div class="card-body">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Status</span>
                    <p class="my-1">
                            <span class="font-weight-bold text-success">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                {{$partial->status}}
                            </span>
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{$partial->installmentItem->installment->installable->business->name}}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Type</span>
                    <p class="my-1">{{ $partial->installmentItem->installment->installable->taxType->name}}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Reason</span>
                    <p class="my-1">{{ $partial->comments}}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Currency</span>
                    <p class="my-1">{{$partial->installmentItem->installment->installable->currency}}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Requested partial Date</span>
                    <p class="my-1">{{ \Carbon\Carbon::parse($partial->payment_due_date)->toDayDateTimeString() }}</p>
                </div>

            </div>

            @if($partial->status == \App\Enum\InstallmentRequestStatus::PENDING)
                <livewire:approval.approval-installment-partial modelName="App\Models\Installment\InstallmentList" modelId="{{ encrypt($partial->payment_id) }}" requestedAmount="{{$partial->amount}}" partialId="{{ encrypt($partial->id) }}" />
            @endif

        </div>
    </div>

@endsection

