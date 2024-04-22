@extends('layouts.master')

@section('title', __('Public Service De-registrations Details'))

@section('content')

    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
               aria-selected="true"> Public Service De-registration</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="approval-tab" data-toggle="tab" href="#approval" role="tab" aria-controls="approval"
               aria-selected="false">Approval History</a>
        </li>
    </ul>

    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade show active pt-3 px-3" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="card shadow-none">
                <div class="card-header text-uppercase font-weight-bold bg-white">
                    {{ __('Public Service De-registrations Details') }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Status') }}</span>
                            <p class="my-1">
                                @if ($deRegistration->status === \App\Enum\PublicService\TemporaryClosureStatus::APPROVED)
                                    <span class="badge badge-success py-1 px-2">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                {{ __('Approved') }}
                             </span>
                                @elseif($deRegistration->status === \App\Enum\PublicService\TemporaryClosureStatus::PENDING)
                                    <span class="badge badge-warning py-1 px-2">
                                <i class="bi bi-clock-history mr-1"></i>
                                {{ __('Pending') }}
                            </span>
                                @elseif($deRegistration->status === \App\Enum\PublicService\TemporaryClosureStatus::REJECTED)
                                    <span class="badge badge-warning py-1 px-2">
                                <i class="bi bi-clock-history mr-1"></i>
                                {{ __('Rejected') }}
                            </span>
                                @else
                                    <span class="badge badge-info py-1 px-2">
                                <i class="bi bi-clock-history mr-1"></i>
                                {{ $deRegistration->status }}
                            </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Plate Number') }}</span>
                            <p class="my-1">{{ $deRegistration->motor->mvr->plate_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Business Name') }}</span>
                            <p class="my-1">{{ $deRegistration->motor->business->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('ZTN Number') }}</span>
                            <p class="my-1">{{ $deRegistration->motor->business->ztn_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Make') }}</span>
                            <p class="my-1">{{ $deRegistration->motor->mvr->chassis->make ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Model') }}</span>
                            <p class="my-1">{{ $deRegistration->motor->mvr->chassis->model_type ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Year') }}</span>
                            <p class="my-1">{{ $deRegistration->motor->mvr->chassis->year ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('De-registration Date') }}</span>
                            <p class="my-1">{{ $deRegistration->de_registration_date ? $deRegistration->de_registration_date->toFormattedDateString() : 'N/A' }}</p>
                        </div>
                        <div class="col-md-9 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Reasons') }}</span>
                            <p class="my-1">{{ $deRegistration->reason ?? 'N/A' }}</p>
                        </div>
                        @if ($deRegistration->grounds_path)
                            <div class="col-md-4">
                                <label class="font-weight-bold text-uppercase">{{ __('Attachments') }}</label>
                                <a target="_blank"
                                   href="{{ route('public-service.de-registrations.file', encrypt($deRegistration->grounds_path)) }}"
                                   class="ml-1">
                                    <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                                         class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>

                                            Grounds Attachment
                                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @include('mvr.registration.reg_info', ['reg' => $deRegistration->motor->mvr])

            <livewire:approval.public-service.de-registration-approval-processing
                    modelName="{{ get_class($deRegistration) }}"
                    modelId="{{ encrypt($deRegistration->id) }}">
            </livewire:approval.public-service.de-registration-approval-processing>

        </div>


        <div class="tab-pane fade m-2" id="approval" role="tabpanel" aria-labelledby="approval-tab">
            <livewire:approval.approval-history-table
                    modelName='{{ \App\Models\PublicService\DeRegistration::class }}'
                    modelId="{{ encrypt($deRegistration->id) }}"/>
        </div>
    </div>

@endsection
