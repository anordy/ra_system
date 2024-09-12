@extends('layouts.master')

@section('title', 'Tax Clearance Details')

@section('content')

    <div class="card p-0 m-0">
        <div class="card-body mt-0 p-2">
            <ul class="nav nav-tabs shadow-sm mb-0" id="waiverContent" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="taxclearance-info-tab" data-toggle="tab" href="#taxclearance-info"
                        role="tab" aria-controls="taxclearance-info" aria-selected="true">Tax Clearance Information </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="debt-infos-tab" data-toggle="tab" href="#debt-infos"
                        role="tab" aria-controls="debt-infos" aria-selected="false">Debts Information</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="approvalHistory-tab" data-toggle="tab" href="#approvalHistory" role="tab"
                        aria-controls="approvalHistory" aria-selected="false">Approval History</a>
                </li>
            </ul>

            <div class="tab-content bg-white border shadow-sm" id="waiverContent">
                <div class="tab-pane fade show active" id="taxclearance-info" role="tabpanel"
                    aria-labelledby="taxclearance-info-tab">
                    
                    <div class="row m-2 pt-3">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Tax Clearance Status</span>
                            <p class="my-1">
                                @if ($taxClearance->status === \App\Enum\TaxClearanceStatus::APPROVED)
                                    <span class="font-weight-bold text-success">
                                        <i class="bi bi-check-circle-fill mr-1"></i>
                                        Approved
                                    </span>
                                @elseif($taxClearance->status === \App\Enum\TaxClearanceStatus::REQUESTED)
                                    <span class="font-weight-bold text-warning">
                                        <i class="bi bi-check-circle-fill mr-1"></i>
                                        Requested
                                    </span>
                                @elseif($taxClearance->status === \App\Enum\TaxClearanceStatus::REJECTED)
                                    <span class="font-weight-bold text-danger">
                                        <i class="bi bi-pen-fill mr-1"></i>
                                        Rejected
                                    </span>
                                @else
                                    <span class="font-weight-bold text-info">
                                        <i class="bi bi-clock-history mr-1"></i>
                                        Waiting Approval
                                    </span>
                                @endif
                            </p>
                        </div>
                    
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Name</span>
                            <p class="my-1">{{ $taxClearance->businessLocation->business->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Branch Name</span>
                            <p class="my-1">{{ $taxClearance->businessLocation->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Category</span>
                            <p class="my-1">{{ $taxClearance->businessLocation->business->category->name  ?? 'N/A'}}</p>
                        </div>
                        @if ($taxClearance->businessLocation->business->alt_mobile)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Alternative Mobile No.</span>
                                <p class="my-1">{{ $taxClearance->businessLocation->business->alt_mobile }}</p>
                            </div>
                        @endif
                        @if ($taxClearance->businessLocation->business->email_address)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Email Address</span>
                                <p class="my-1">{{ $taxClearance->businessLocation->business->email }}</p>
                            </div>
                        @endif
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Place of Business</span>
                            <p class="my-1">
                                {{ $taxClearance->businessLocation->region->name ?? 'N/A' }},
                                {{ $taxClearance->businessLocation->district->name ?? 'N/A' }},
                                {{ $taxClearance->businessLocation->ward->name ?? 'N/A' }}
                            </p>
                        </div>
                        @if ($taxClearance->status === App\Enum\TaxClearanceStatus::APPROVED)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Tax Clearance Pdf</span>
                                <div class="my-1">
                                    <a target="_blank" href="{{ route('tax-clearance.certificate', encrypt($taxClearance->id)) }}"
                                        class="btn btn-info btn-sm">
                                        <i class="bi bi-download mr-1"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-8 mb-3">
                            <span class="font-weight-bold text-uppercase">Reason</span>
                            <p class="my-1">
                                {{ $taxClearance->reason ?? 'N/A' }}.
                            </p>
                        </div>
                    </div>
                    
                    <livewire:approval.tax-clearence-approval-processing modelName='App\Models\TaxClearanceRequest'
                        modelId="{{ encrypt($taxClearance->id) }}" />
                </div>
                <div class="tab-pane fade show" id="debt-infos" role="tabpanel" aria-labelledby="debt-infos-tab">
                    @include('tax-clearance.includes.tax_clearence_info')
                </div>
                <div class="tab-pane fade" id="approvalHistory" role="tabpanel" aria-labelledby="approvalHistory-tab">
                    <livewire:approval.approval-history-table modelName='App\Models\TaxClearanceRequest'
                        modelId="{{ encrypt($taxClearance->id) }}" />
                </div>
            </div>
        </div>
    </div>
@endsection

