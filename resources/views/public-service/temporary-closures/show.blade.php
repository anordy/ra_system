@extends('layouts.master')

@section('title', __('Public Service Temporary Closures Details'))

@section('content')

    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
               aria-selected="true"> Public Service Temporary Closure</a>
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
                    {{ __('Public Service Temporary Closures Details') }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Status') }}</span>
                            <p class="my-1">
                                @if ($closure->status === \App\Enum\PublicService\TemporaryClosureStatus::APPROVED)
                                    <span class="badge badge-success py-1 px-2">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                {{ __('Approved') }}
                             </span>
                                @elseif($closure->status === \App\Enum\PublicService\TemporaryClosureStatus::PENDING)
                                    <span class="badge badge-warning py-1 px-2">
                                <i class="bi bi-clock-history mr-1"></i>
                                {{ __('Pending') }}
                            </span>
                                @elseif($closure->status === \App\Enum\PublicService\TemporaryClosureStatus::REJECTED)
                                    <span class="badge badge-warning py-1 px-2">
                                <i class="bi bi-clock-history mr-1"></i>
                                {{ __('Rejected') }}
                            </span>
                                @else
                                    <span class="badge badge-info py-1 px-2">
                                <i class="bi bi-clock-history mr-1"></i>
                                {{ $closure->status }}
                            </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Plate Number') }}</span>
                            <p class="my-1">{{ $closure->motor->mvr->plate_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Closing Date') }}</span>
                            <p class="my-1">{{ $closure->closing_date ? $closure->closing_date->toFormattedDateString() : 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Opening Date') }}</span>
                            <p class="my-1">{{ $closure->opening_date ? $closure->opening_date->toFormattedDateString() : 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Business Name') }}</span>
                            <p class="my-1">{{ $closure->motor->business->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('ZTN Number') }}</span>
                            <p class="my-1">{{ $closure->motor->business->ztn_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Make') }}</span>
                            <p class="my-1">{{ $closure->motor->mvr->chassis->make ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Model') }}</span>
                            <p class="my-1">{{ $closure->motor->mvr->chassis->model_type ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Year') }}</span>
                            <p class="my-1">{{ $closure->motor->mvr->chassis->year ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @include('mvr.registration.reg_info', ['reg' => $closure->motor->mvr])

            <livewire:approval.public-service.temporary-closure-approval-processing
                    modelName="{{ get_class($closure) }}"
                    modelId="{{ encrypt($closure->id) }}">
            </livewire:approval.public-service.temporary-closure-approval-processing>

        </div>


        <div class="tab-pane fade m-2" id="approval" role="tabpanel" aria-labelledby="approval-tab">
            <livewire:approval.approval-history-table
                    modelName='{{ \App\Models\PublicService\TemporaryClosure::class }}'
                    modelId="{{ encrypt($closure->id) }}"/>
        </div>
    </div>

@endsection
