@extends('layouts.master')

@section('title', 'Dispute Details')

@section('content')
    <div class="card p-0 m-0">
        <div class="card-body mt-0 p-2">
            <ul class="nav nav-tabs shadow-sm" id="waiverContent" role="tablist" style="margin-bottom: 0;">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="disputeInfo-tab" data-toggle="tab" href="#disputeInfo" role="tab"
                        aria-controls="disputeInfo" aria-selected="true">Dispute Information</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="approvalHistory-tab" data-toggle="tab" href="#approvalHistory" role="tab"
                        aria-controls="approvalHistory" aria-selected="false">Approval History</a>
                </li>
            </ul>
            <div class="tab-content bg-white border shadow-sm" id="waiverContent">
                <div class="tab-pane fade show active" id="disputeInfo" role="tabpanel" aria-labelledby="disputeInfo-tab">
                    @include('assesments.dispute.includes.dispute_info')
                    <livewire:approval.objection-approval-processing modelName='App\Models\Disputes\Dispute'
                        modelId="{{ encrypt($dispute->id) }}" />
                </div>

                <div class="tab-pane fade" id="approvalHistory" role="tabpanel" aria-labelledby="approvalHistory-tab">
                    <livewire:approval.approval-history-table modelName='App\Models\Disputes\Dispute'
                        modelId="{{ encrypt($dispute->id) }}" />
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
