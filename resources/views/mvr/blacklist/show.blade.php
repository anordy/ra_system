@extends('layouts.master')

@section('title', 'View BlackList')

@section('content')

    <ul class="nav nav-tabs shadow-sm mb-0" id="myTab">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" aria-controls="home" role="tab"
               aria-selected="true">
                Base Information
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

            <div class="card mt-3">
                <div class="card-header">
                    Blacklist Information
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <div class="row my-2">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Initiator Type</span>
                                <p class="my-1">{{ formatEnum($blacklist->initiator_type ?? 'N/A') }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Type</span>
                                <p class="my-1">{{ formatEnum($blacklist->type ?? 'N/A') }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Initiated By</span>
                                <p class="my-1">{{ $blacklist->user->fullname() ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Initiated Date</span>
                                <p class="my-1">{{ $blacklist->created_at ? \Carbon\Carbon::create($blacklist->created_at)->format('d-M-Y') : 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Blocking State</span>
                                <p class="my-1">{{ $blacklist->is_blocking ? 'Yes' : 'No' }}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <span class="font-weight-bold text-uppercase">Reasons</span>
                                <p class="my-1">{{ $blacklist->reasons ?? 'N/A' }}</p>
                            </div>
                            @if($blacklist->evidence_path)
                                <div class="col-md-3">
                                    <div class="p-2 mb-3 d-flex rounded-sm align-items-center file-item">
                                        <i class="bi bi-file-earmark-pdf-fill px-2 file-icon"></i>
                                        <a target="_blank"
                                           href="{{ route('mvr.files', encrypt($blacklist->evidence_path)) }}"
                                           class="ml-1">
                                            Evidence File
                                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($blacklist->type === \App\Enum\Mvr\MvrBlacklistType::MVR)
                @include('mvr.reg_info', ['reg' => $blacklist->blacklist])
            @elseif($blacklist->type === \App\Enum\Mvr\MvrBlacklistType::DL)
                @include('driver-license.includes.license_info', ['license' => $blacklist->blacklist])
            @else
                <span>Invalid Blacklist Type</span>
            @endif


            <livewire:approval.mvr.blacklist-approval-processing modelName="{{ \App\Models\MvrBlacklist::class }}"
                                                                modelId="{{ encrypt($blacklist->id) }}"/>
        </div>
        <div class="tab-pane fade p-3" id="approval" role="tabpanel" aria-labelledby="approval-tab">
            <livewire:approval.approval-history-table modelName="{{ \App\Models\MvrBlacklist::class }}"
                                                      modelId="{{ encrypt($blacklist->id) }}"/>
        </div>
    </div>

@endsection

