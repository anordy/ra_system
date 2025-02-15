@extends('layouts.master')

@section('title', 'Motor Vehicle Temporary Transportation')

@section('content')

    <ul class="nav nav-tabs shadow-sm mb-0" id="myTab">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" aria-controls="home" role="tab"
               aria-selected="true">
                Request Information
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="approval-tab" data-toggle="tab" href="#approval" aria-controls="approval"
               role="tab" aria-selected="true">
                Approval History
            </a>
        </li>
    </ul>

    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade p-3 show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="card p-0 m-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                    <div class="text-uppercase font-weight-bold">{{ __('Motor Vehicle Temporary Transportation Details') }}</div>
                    @if($transport->status == \App\Enum\MvrTemporaryTransportStatus::APPROVED)
                        <div class="card-tools">
                            <a href="{{ route('mvr.temporary-transports.letter', encrypt($transport->id)) }}" class="btn btn-primary" target="_blank">
                                <i class="bi bi-filetype-pdf mr-1"></i>
                                View Transport Letter
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body mt-0 p-2 row">
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Request Status</span>
                        <p class="my-1">@include('mvr.temporary-transports.includes.status', ['value' => $transport->status])</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Date of Travel</span>
                        <p class="my-1">{{ $transport->date_of_travel->toDateString() ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Date of Return</span>
                        <p class="my-1">{{ $transport->date_of_return->toDateString() ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Reasons</span>
                        <p class="my-1">{{ $transport->reasons ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Requested By</span>
                        <p class="my-1">{{ $transport->taxpayer->fullName ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Reference No.</span>
                        <p class="my-1">{{ $transport->taxpayer->reference_no ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Requested At</span>
                        <p class="my-1">{{ $transport->created_at->toFormattedDateString() ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="card-header">
                    Attachments
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="card-body">
                            <div class="row">
                                @foreach($transport->files as $file)
                                    <div class="col-md-3 pr-0">
                                        <div class="p-2 mb-0 d-flex rounded-sm align-items-center file-item">
                                            <i class="bi bi-file-earmark-pdf-fill px-2 file-icon"></i>
                                            <a target="_blank"
                                               href="{{ route('mvr.files', encrypt($file->location)) }}"
                                               class="ml-1">
                                                {{ __($file->name) }}
                                                <i class="bi bi-arrow-up-right-square ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('mvr.temporary-transports.temporary_transport_info', ['reg' => $transport->mvr])
            @can('mvr-approve-temporary-transports')
                <livewire:approval.mvr.temporary-transport-approval-processing
                    modelName='App\Models\MvrTemporaryTransport'
                    modelId="{{ encrypt($transport->id) }}" />
            @endcan
        </div>
        <div class="tab-pane fade p-3" id="approval" role="tabpanel" aria-labelledby="approval-tab">
            <livewire:approval.approval-history-table
                    modelName='App\Models\MvrTemporaryTransport'
                    modelId="{{ encrypt($transport->id) }}" />
        </div>
    </div>
@endsection