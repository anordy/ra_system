@extends('layouts.master')

@section('title', __('Road License Information'))

@section('content')

    <ul class="nav nav-tabs shadow-sm mb-0" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" aria-controls="home" role="tab"
               aria-selected="true">
                Registration Information
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="approval-tab" data-toggle="tab" href="#approval" aria-controls="approval"
               role="tab" aria-selected="true">
                Approval History
            </a>
        </li>
    </ul>
    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade p-3 show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="card">
                <div class="card-header text-uppercase font-weight-bold bg-white">
                    {{ __('Road License Information') }}
                </div>

                <div class="card-body">
                    <div class="row my-2">
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Status</span>
                            <p class="my-1">
                                @if ($roadLicense->status === \App\Enum\RoadLicenseStatus::PENDING)
                                    <span class="badge badge-info py-1 px-2 font-percent-85">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            Pending
                        </span>
                                @elseif($roadLicense->status === \App\Enum\RoadLicenseStatus::APPROVED)
                                    <span class="badge badge-success py-1 px-2 font-percent-85">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            Approved
                            </span>
                                @elseif($roadLicense->status === \App\Enum\MvrRegistrationStatus::CORRECTION)
                                    <span class="badge badge-warning py-1 px-2 font-percent-85">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                For Correction
                            </span>
                                @else
                                    <span class="badge badge-primary py-1 px-2 font-percent-85">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                {{ $roadLicense->status }}
                            </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Taxpayer</span>
                            <p class="my-1">{{ $roadLicense->taxpayer->fullname ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Inspection Date</span>
                            <p class="my-1">{{ $roadLicense->inspection_date ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Issued Date</span>
                            <p class="my-1">{{ $roadLicense->issued_date ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Expiry Date</span>
                            <p class="my-1">{{ $roadLicense->expire_date ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Pass Mark</span>
                            <p class="my-1">{{ $roadLicense->pass_mark ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Passengers Number</span>
                            <p class="my-1">{{ $roadLicense->passengers_no ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Unique Number</span>
                            <p class="my-1">{{ $roadLicense->urn ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                @include('road-license.mvr_info', ['reg' => $roadLicense->registration])

                <livewire:approval.road-license.approval-processing modelName="{{ \App\Models\RoadLicense\RoadLicense::class  }}"
                                                                    modelId="{{ encrypt($roadLicense->id) }}"/>

            </div>

        </div>
        <div class="tab-pane fade p-3" id="approval" role="tabpanel" aria-labelledby="approval-tab">
            <livewire:approval.approval-history-table modelName="{{ \App\Models\RoadLicense\RoadLicense::class  }}"
                                                      modelId="{{ encrypt($roadLicense->id) }}"/>
        </div>
    </div>
@endsection