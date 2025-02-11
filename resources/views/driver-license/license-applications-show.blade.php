@extends('layouts.master')

@section('title', $title ?? 'N/A')

@section('content')
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab"
               aria-controls="home" aria-selected="true">Driver Licence Application</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="approval-tab" data-toggle="tab" href="#approval" role="tab"
               aria-controls="home">Approval History</a>
        </li>
    </ul>

    <div class="tab-content border bg-white" id="myTabContent">
        <div class="tab-pane p-3 show active" id="all" role="tabpanel" aria-labelledby="all-tab">
            <div class="row">
                <div class="col-md-12 mb-3">
                    @if (
                        $application->status === \App\Models\DlApplicationStatus::STATUS_PENDING_PAYMENT ||
                            $application->payment_status === \App\Enum\PaymentStatus::PENDING)
                        @livewire('drivers-license.payment.fee-payment', ['license' => $application])
                    @endif
                </div>
            </div>

            @include('driver-license.includes.license_info', ['application' => $application])


            <livewire:approval.mvr.driver-license-approval-processing
                    modelName='App\Models\DlLicenseApplication' modelId="{{ encrypt($application->id) }}"/>

        </div>
        <div class="tab-pane fade p-3" id="approval" role="tabpanel" aria-labelledby="approval-tab">
            <livewire:approval.approval-history-table modelName='App\Models\DlLicenseApplication'
                                                      modelId="{{ encrypt($application->id) }}"/>
        </div>
    </div>



@endsection
