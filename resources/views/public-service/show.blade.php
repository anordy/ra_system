@extends('layouts.master')

@section('title', 'View Public Service Registration')

@section('content')

    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
               aria-selected="true"> Public Service Registration Information</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="approval-tab" data-toggle="tab" href="#approval" role="tab" aria-controls="approval"
               aria-selected="false">Approval History</a>
        </li>
    </ul>

    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-uppercase">Public Service Information</h5>
                </div>
                <div class="card-body">
                    <div class="row m-2 pt-3">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Property Status</span>
                            <p class="my-1">
                                @if ($registration->status === \App\Enum\PublicServiceMotorStatus::REGISTERED)
                                    <span class="badge badge-success py-1 px-2">
                                        <i class="bi bi-check-circle-fill mr-1"></i>
                                        Registered
                                     </span>
                                @elseif($registration->status === \App\Enum\PublicServiceMotorStatus::PENDING)
                                    <span class="badge badge-warning py-1 px-2">
                                        <i class="bi bi-clock-history mr-1"></i>
                                        Pending
                                    </span>
                                @elseif($registration->status === \App\Enum\PublicServiceMotorStatus::DEREGISTERED)
                                    <span class="badge badge-warning py-1 px-2">
                                        <i class="bi bi-clock-history mr-1"></i>
                                        De-registered
                                    </span>
                                @else
                                    <span class="badge badge-info py-1 px-2">
                                        <i class="bi bi-clock-history mr-1"></i>
                                        {{ $registration->status  }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Plate Number</span>
                            <p class="my-1">{{ $registration->mvr->plate_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Vehicle Registration Type</span>
                            <p class="my-1">{{ $registration->mvr->regtype->name }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Vehicle Class</span>
                            <p class="my-1">{{ $registration->mvr->class->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Name</span>
                            <p class="my-1">{{ $registration->business->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Register's Name</span>
                            <p class="my-1">{{ $registration->taxpayer->fullname ?? 'N/A' }}</p>
                        </div>

                        @if ($registration->permission_document_path)
                            <div class="col-md-4">
                                <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                                     class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                    <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                    <a target="_blank"
                                       href="{{ route('public-service.registrations.file', encrypt($registration->permission_document_path)) }}"
                                       class="ml-1">
                                        Permission Document
                                        <i class="bi bi-arrow-up-right-square ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($registration->road_license_path)
                            <div class="col-md-4">
                                <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                                     class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                    <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                    <a target="_blank"
                                       href="{{ route('public-service.registrations.file', encrypt($registration->road_license_path)) }}"
                                       class="ml-1">
                                        Road License
                                        <i class="bi bi-arrow-up-right-square ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>

            {{--            <livewire:approval.property-tax-approval-processing modelName="{{ get_class($registration) }}"--}}
            {{--                                                                modelId="{{ encrypt($registration->id) }}"></livewire:approval.property-tax-approval-processing>--}}

        </div>


        <div class="tab-pane fade m-2" id="approval" role="tabpanel" aria-labelledby="approval-tab">
            <livewire:approval.approval-history-table
                    modelName='{{ \App\Models\PublicService\PublicServiceMotor::class }}'
                    modelId="{{ encrypt($registration->id) }}"/>
        </div>
    </div>

@endsection
