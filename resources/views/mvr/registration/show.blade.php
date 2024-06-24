@extends('layouts.master')

@section('title', 'Motor Vehicle Registration Details')

@section('content')

    @if($motorVehicle->status === \App\Enum\MvrRegistrationStatus::STATUS_PENDING_PAYMENT || $motorVehicle->status === \App\Enum\MvrRegistrationStatus::STATUS_REGISTERED
        || $motorVehicle->status === \App\Enum\MvrRegistrationStatus::STATUS_PLATE_NUMBER_PRINTING)
        @livewire('mvr.fee-payment', ['motorVehicle' => $motorVehicle])
    @endif

    <ul class="nav nav-tabs shadow-sm mb-0" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" aria-controls="home" role="tab"
               aria-selected="true">
                Registration Information
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="plate-number-tab" data-toggle="tab" href="#plate-number"
               aria-controls="plate-number"
               role="tab" aria-selected="true">
                Plate Number History
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
            @include('mvr.registration.reg_info', ['reg' => $motorVehicle])
            @include('mvr.registration.chassis_info', ['motor_vehicle' => $motorVehicle->chassis])
            <livewire:approval.mvr.registration-approval-processing modelName='App\Models\MvrRegistration'
                                                                    modelId="{{ encrypt($motorVehicle->id) }}"/>
        </div>
        <div class="tab-pane fade p-3" id="plate-number" role="tabpanel" aria-labelledby="plate-number-tab">
            <div class="card mt-3">
                <div class="card-header font-weight-bold bg-white">
                    Plate Number Histories
                </div>
                <div class="card-body">
                    <table width="50%" class="table table-sm table-striped">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Owner's Name</th>
                            <th>Owner's TIN</th>
                            <th>Plate Number</th>
                            <th>Registration Number</th>
                            <th>Date of Registration</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($plateHistories as $i => $plateHistory)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>
                                    @if($plateHistory->agent)
                                        @if($plateHistory->is_agent_registration)
                                            @if($plateHistory->use_company_name)
                                                {{strtoupper($plateHistory->agent->company_name ?? 'N/A')}}
                                            @else
                                                {{strtoupper($plateHistory->taxpayer->fullname ?? 'N/A')}}
                                            @endif
                                        @else
                                            @if($plateHistory->tin)
                                                {{strtoupper($plateHistory->tin->fullname ?? $plateHistory->tin->taxpayer_name )}}
                                            @else
                                                {{ 'N/A' }}
                                            @endif
                                        @endif
                                    @else
                                        {{strtoupper($plateHistory->taxpayer->fullname ?? 'N/A')}}
                                    @endif
                                </td>
                                <td>{{ $plateHistory->registrant_tin ?? 'N/A'  }}</td>
                                <td>{{ $plateHistory->plate_number ?? 'N/A'  }}</td>
                                <td>{{ $plateHistory->registration_number ?? 'N/A'  }}</td>
                                <td>{{ \Carbon\Carbon::create($plateHistory->registered_at)->format('d M Y')  }}</td>
                                <td> @if($plateHistory->status === \App\Enum\MvrRegistrationStatus::PENDING)
                                        <span class="badge badge-info py-1 px-2">
                                            <i class="bi bi-check-circle-fill mr-1"></i>
                                            {{ __('Pending') }}
                                        </span>
                                    @elseif($plateHistory->status === \App\Enum\MvrRegistrationStatus::STATUS_REGISTERED)
                                        <span class="badge badge-success py-1 px-2">
                                            <i class="bi bi-check-circle-fill mr-1"></i>
                                            {{ __('Registered') }}
                                        </span>
                                    @elseif($plateHistory->status === \App\Enum\MvrRegistrationStatus::STATUS_RETIRED)
                                        <span class="badge badge-danger py-1 px-2">
                                            <i class="bi bi-check-circle-fill mr-1"></i>
                                            {{ __('Retired') }}
                                        </span>
                                    @elseif($plateHistory->status === \App\Enum\MvrRegistrationStatus::CORRECTION)
                                        <span class="badge badge-warning py-1 px-2">
                                            <i class="bi bi-check-circle-fill mr-1"></i>
                                            {{ __('For Corrections') }}
                                        </span>
                                    @else
                                        <span class="badge badge-primary py-1 px-2">
                                            <i class="bi bi-check-circle-fill mr-1"></i>
                                            {{ $plateHistory->status }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade p-3" id="approval" role="tabpanel" aria-labelledby="approval-tab">
            <livewire:approval.approval-history-table modelName='App\Models\MvrRegistration'
                                                      modelId="{{ encrypt($motorVehicle->id) }}"/>
        </div>
    </div>

@endsection