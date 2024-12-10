@extends('layouts.master')

@section('title', 'Business De-Registration Details')

@section('content')
    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
               aria-selected="true">Business Information Overview</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history"
               aria-selected="true">Approval History</a>
        </li>
    </ul>

    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $business->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">ZTN Number</span>
                    <p class="my-1">{{ $business->ztn_location_number }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">VRN Number</span>
                    <p class="my-1">{{ $business->vrn }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Country</span>
                    <p class="my-1">{{ $business->country->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Requested Date</span>
                    <p class="my-1">{{ $deRegistration->created_at ? \Carbon\Carbon::create($deRegistration->created_at)->format('d M, Y') : 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Status</span>
                    <p class="my-1">
                            <span class="font-weight-bold text-info">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                {{ strtoupper($deRegistration->status ?? 'N/A') }}
                            </span>
                    </p>
                </div>
                @if($deRegistration->approved_on)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Approved Date</span>
                        <p class="my-1">{{  \Carbon\Carbon::create($deRegistration->approved_on)->format('d M, Y') ?? 'N/A' }}</p>
                    </div>
                @endif
                @if($deRegistration->rejected_on)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Rejected Date</span>
                        <p class="my-1">{{ \Carbon\Carbon::create($deRegistration->rejected_on)->format('d M, Y') ?? 'N/A' }}</p>
                    </div>
                @endif

                <div class="col-md-12 mb-3">
                    <span class="font-weight-bold text-uppercase">Reason</span>
                    <p class="my-1">{{ $deRegistration->reason }}</p>
                </div>
            </div>

            <div class="mt-4 mx-4">
                <span class="font-weight-bold text-uppercase">Outstanding Liabilities</span>
                <div class="mt-2">
                    <livewire:non-tax-resident.returns.business-returns-table
                            ntrBusinessId="{{ encrypt($business->id) }}"/>
                </div>

            </div>

            <div class="mt-4 mx-4">
                <livewire:approval.non-tax-resident.ntr-business-deregistration-approval-processing
                        modelName="{{ \App\Models\Ntr\NtrBusinessDeregistration::class }}"
                        modelId="{{ encrypt($deRegistration->id) }}"/>
            </div>

        </div>
        <div class="tab-pane fade show" id="history" role="tabpanel" aria-labelledby="history-tab">
            <div class="mt-4 mx-4">
                <livewire:approval.approval-history-table
                        modelName="{{ \App\Models\Ntr\NtrBusinessDeregistration::class }}"
                        modelId="{{ encrypt($deRegistration->id) }}"/>
            </div>
        </div>
    </div>

@endsection