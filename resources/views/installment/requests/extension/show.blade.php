@extends('layouts.master')

@section('title', 'View Installment Request')

@section('content')

{{--    <div class="container-fluid">--}}
{{--        <div class="row py-4 alert alert-secondary bg-alt rounded-0 shadow-sm border-success">--}}

{{--            <div class="col-md-3">--}}
{{--                <span class="font-weight-bold text-uppercase">Payment Due Date</span>--}}
{{--                <p class="my-1">{{ $bill->created_at->addMonth(1)->format('d-m-Y') }}</p>--}}
{{--            </div>--}}
{{--            --}}{{--                @if($activeItem)--}}
{{--            <div class="col-md-2">--}}
{{--                <span class="font-weight-bold text-uppercase">Amount</span>--}}
{{--                <p class="my-1">{{$bill->amount}}</p>--}}
{{--            </div>--}}
{{--            <div class="col-md-2">--}}
{{--                <span class="font-weight-bold text-uppercase">Control No</span>--}}
{{--                <p class="my-1">{{$bill->control_number}}</p>--}}
{{--            </div>--}}
{{--            <div class="col-md-3">--}}
{{--                <span class="font-weight-bold text-uppercase">Status</span>--}}
{{--                <p>--}}
{{--                            <span class="font-weight-bold {{$bill->status != \App\Models\Offence\Offence::PAID ? 'text-info' : 'text-success'}}">--}}
{{--                                <i class="bi bi-check-circle-fill mr-1"></i>--}}
{{--                                {{$bill->status}}--}}
{{--                            </span>--}}
{{--                </p>--}}
{{--            </div>--}}
{{--            <div class="col-md-2 d-flex justify-content-end">--}}
{{--                <span class="font-weight-bold text-uppercase"> </span>--}}
{{--                <p class="my-1">--}}
{{--                    @if($bill->status != \App\Models\Offence\Offence::PAID)--}}
{{--                        <a target="_blank" href="{{ route('bill.invoice', encrypt($bill->id)) }}"--}}
{{--                           class="btn btn-primary btn-sm pl-3 pr-3 font-weight-bold">--}}
{{--                            <i class="bi bi-download mr-3"></i><u>Download Bill</u>--}}
{{--                        </a>--}}
{{--                    @else--}}
{{--                        <a target="_blank" href="{{ route('bill.receipt', encrypt($bill->id)) }}"--}}
{{--                           class="btn btn-primary btn-sm pl-3 pr-3 font-weight-bold">--}}
{{--                            <i class="bi bi-download mr-3"></i><u>Download Receipt</u>--}}
{{--                        </a>--}}
{{--                    @endif--}}
{{--                </p>--}}
{{--            </div>--}}


{{--        </div>--}}
{{--    </div>--}}

    <div class="card rounded-0 mt-3">
        <div class="card-header bg-white">
            Extension Details
        </div>
        <div class="card-body">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Status</span>
                    <p class="my-1">
                            <span class="font-weight-bold text-success">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                {{$extension->status}}
                            </span>
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{$extension->installment->installable->business->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Type</span>
                    <p class="my-1">{{ $extension->installment->installable->taxType->name}}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Reason</span>
                    <p class="my-1">{{ $extension->reasons}}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Currency</span>
                    <p class="my-1">{{$extension->installment->installable->currency}}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Requested Extension Date</span>
                    <p class="my-1">{{ \Carbon\Carbon::parse($extension->extension_date)->toDayDateTimeString() }}</p>
                </div>

            </div>

            @if($extension->status == \App\Enum\InstallmentRequestStatus::PENDING)
                <livewire:approval.approval-installment-extension modelName="{{ get_class($extension) }}" modelId="{{ encrypt($extension->id) }}" />
            @endif

        </div>
    </div>

@endsection

