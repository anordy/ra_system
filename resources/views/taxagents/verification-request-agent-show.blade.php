@extends('layouts.master')

@section('title', 'Tax Consultants ')

@section('content')
    <div class="card">
        <div class="card-header">
            <h6>Registration Details</h6>
            <div class="card-tools">
                <a class="btn btn-info" href="{{ route('taxagents.requests') }}">
                    <i class="bi bi-arrow-return-left mr-2"></i>
                    Back
                </a>
            </div>
        </div>


        <div class="card-body">
            <div class="card p-0 m-0">
                <div class="card-body mt-0 p-2">
                    <nav class="nav nav-tabs mt-0 border-top-0">
                        <a href="#tab1" class="nav-item nav-link font-weight-bold active">Tax Consultant
                            Information</a>
                        <a href="#tab2" class="nav-item nav-link font-weight-bold">Approval History</a>
                        <a href="#tab3" class="nav-item nav-link font-weight-bold">Renew Requests</a>
                    </nav>
                    <div class="tab-content px-2 border border-top-0 pt-3 pb-2">
                        <div id="tab1" class="tab-pane fade active show">
                            @include('taxagents.includes.show')
                            @if(empty($fee))
                                <div class="mx-1 p-2">
                                    <div class="row py-2 alert alert-danger rounded-0 shadow-sm border-danger">
                                        <div class="col-md-6">
                                            <span class="font-weight-bold text-uppercase">Notice</span>
                                            <p class="my-1">Please kindly add registration fee before approving any
                                                request</p>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="font-weight-bold text-uppercase">Action</span>
                                            <p class="my-1">
                                                <a class="btn btn-primary" href="{{ route('settings.tax-consultant-fee') }}">
                                                    <i class="bi bi-plus-square-fill mr-2"></i>
                                                    Add Fee
                                                </a>
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            @else
                                <livewire:tax-agent.approval.registration.approval-processing
                                        modelName='App\Models\TaxAgent' modelId="{{ encrypt($agent->id) }}"/>
                            @endif
                        </div>
                        <div id="tab2" class="tab-pane fade">
                            <livewire:tax-agent.approval.registration.approval-history-table
                                    modelName='App\Models\TaxAgent' modelId="{{ encrypt($agent->id) }}"/>
                        </div>

                        <div id="tab3" class="tab-pane fade">
                            dmfnnffff
                        </div>

                    </div>
                </div>
            </div>

        </div>


    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $(".nav-tabs a").click(function () {
                $(this).tab('show');
            });
        });
    </script>
@endsection
