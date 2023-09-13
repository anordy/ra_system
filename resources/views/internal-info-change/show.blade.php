@extends('layouts.master')

@php
    $info_type = ucfirst(str_replace('_', ' ', $info->type));
@endphp

@section('title', "{$info_type} Information Change for {$info->business->name}")

@section('content')
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
            aria-selected="true">Business Information</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history"
            aria-selected="false">Approval Histories</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active card p-2" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Internal Business Information Change
        </div>
        <div class="card-body">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $info->business->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Branch</span>
                    <p class="my-1">{{ $info->location->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Information Type</span>
                    <p class="my-1">{{ $info->type ? ucfirst(str_replace('_', ' ',$info->type)) : 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Triggered By</span>
                    <p class="my-1">{{ $info->staff->fullname ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Triggered On</span>
                    <p class="my-1">{{ $info->created_at ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Approved On</span>
                    <p class="my-1">{{ $info->approved_on ?? 'N/A' }}</p>
                </div>
            </div>

            @if ($info->status === \App\Enum\InternalInfoChangeStatus::APPROVED)
            <div class="row m-2 pt-3">
                @if ($info->type === \App\Enum\InternalInfoType::HOTEL_STARS)
                <div class="col-md-12">
                    <table class="table table-bordered table-striped table-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="text-left font-weight-bold text-uppercase">Hotel Stars Rating Change</label>
                        </div>
                        <thead>
                            <th style="width: 30%">Current Star Rating</th>
                            <th style="width: 30%">New Star Rating</th>
                            <th style="width: 20%">Status</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ json_decode($info->old_values)->name ?? 'N/A' }}</td>
                                <td>{{ json_decode($info->new_values)->name ?? 'N/A' }}</td>
                                @if (json_decode($info->old_values)->name == json_decode($info->new_values)->name)
                                    <td class="table-primary">Unchanged</td>
                                @else
                                    <td class="table-success">Changed</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif

                    @if ($info->type === \App\Enum\InternalInfoType::EFFECTIVE_DATE)
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped table-sm">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="text-left font-weight-bold text-uppercase">Effective Date Change</label>
                                </div>
                                <thead>
                                <th style="width: 30%">Current Effective Date</th>
                                <th style="width: 30%">New Effective Date</th>
                                <th style="width: 20%">Status</th>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ json_decode($info->old_values)->effective_date ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::create(json_decode($info->new_values)->effective_date)->format('d-M-Y') ?? 'N/A' }}</td>
                                    @if (json_decode($info->old_values)->effective_date == json_decode($info->new_values)->effective_date)
                                        <td class="table-primary">Unchanged</td>
                                    @else
                                        <td class="table-success">Changed</td>
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if($info->type === \App\Enum\InternalInfoType::TAX_TYPE)
                        @php
                            $oldTaxes = json_decode($info->old_values, TRUE);
                            $newTaxes = json_decode($info->new_values, TRUE)['selectedTaxTypes'];
                        @endphp

                        <div class="col-md-12">
                            <table class="table table-bordered table-striped table-sm">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="text-left font-weight-bold text-uppercase">Current Tax Types</label>
                                </div>
                                <thead>
                                <th style="width: 30%">Tax Type Name</th>
                                <th style="width: 30%">Currency</th>
                                <th style="width: 30%">Sub VAT Category (If available)</th>
                                </thead>
                                <tbody>
                                    @foreach($oldTaxes as $oldTax)
                                        <tr>
                                            <td>{{ getTaxTypeName($oldTax['tax_type_id'])  }}</td>
                                            <td>{{ $oldTax['currency']  }}</td>
                                            <td>{{ getSubVatName($oldTax['sub_vat_id'] ?? null) ?? 'N/A'  }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped table-sm">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="text-left font-weight-bold text-uppercase">Current Tax Types</label>
                                </div>
                                <thead>
                                <th style="width: 30%">Tax Type Name</th>
                                <th style="width: 30%">Currency</th>
                                <th style="width: 30%">Sub VAT Category (If available)</th>
                                </thead>
                                <tbody>
                                @foreach($newTaxes as $newTax)
                                    <tr>
                                        <td>{{ getTaxTypeName($newTax['tax_type_id'])  }}</td>
                                        <td>{{ $newTax['currency']  }}</td>
                                        <td>{{ getSubVatName($newTax['sub_vat_id'] ?? null) ?? 'N/A'  }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    @endif

                    @if ($info->type === \App\Enum\InternalInfoType::ELECTRIC)
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped table-sm">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="text-left font-weight-bold text-uppercase">Business Electric Change</label>
                                </div>
                                <thead>
                                <th style="width: 30%">Current Business Electric Status</th>
                                <th style="width: 30%">New Business Electric Status</th>
                                <th style="width: 20%">Status</th>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ json_decode($info->old_values) ? 'Yes' : 'No' }}</td>
                                    <td>{{ json_decode($info->new_values) ? 'Yes' : 'No' }}</td>
                                    @if (json_decode($info->old_values) == json_decode($info->new_values))
                                        <td class="table-primary">Unchanged</td>
                                    @else
                                        <td class="table-success">Changed</td>
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if ($info->type === \App\Enum\InternalInfoType::LTO)
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped table-sm">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="text-left font-weight-bold text-uppercase">Business LTO Change</label>
                                </div>
                                <thead>
                                <th style="width: 30%">Current Business LTO Status</th>
                                <th style="width: 30%">New Business LTO Status</th>
                                <th style="width: 20%">Status</th>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ json_decode($info->old_values) ? 'Yes' : 'No' }}</td>
                                    <td>{{ json_decode($info->new_values) ? 'Yes' : 'No' }}</td>
                                    @if (json_decode($info->old_values) == json_decode($info->new_values))
                                        <td class="table-primary">Unchanged</td>
                                    @else
                                        <td class="table-success">Changed</td>
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif

            </div>  
            @endif
          

            <livewire:approval.internal-business-info-change-processing modelName="{{ get_class($info) }}" modelId="{{ encrypt($info->id) }}"></livewire:approval.internal-business-info-change-processing>

        </div>
    </div>

    <div class="tab-pane fade card p-2" id="history" role="tabpanel" aria-labelledby="history-tab">
        <div class="card">
            <div class="card-body">
                <livewire:approval.approval-history-table modelName='{{ get_class($info) }}'
                    modelId="{{ encrypt($info->id) }}" />
            </div>
        </div>
    </div>
@endsection
