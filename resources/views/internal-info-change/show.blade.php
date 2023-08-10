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
                                <td>{{ json_decode($info->old_values)->no_of_stars }} Star</td>
                                <td>{{ json_decode($info->new_values)->no_of_stars }} Star</td>
                                @if (json_decode($info->old_values)->no_of_stars == json_decode($info->new_values)->no_of_stars)
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
