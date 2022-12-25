@extends('layouts.master')

@section('title', 'Tax Consultants ')

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header">
            <h6>Tax Consultant Renew Details</h6>
            <div class="card-tools">
                <a class="btn btn-outline-info" href="{{ route('taxagents.renew') }}">Back</a>
            </div>
        </div>
        <div class="card-body mt-0 p-2">

            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Renew Tax Consultant Information</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Approval History</a>
            </nav>
            <div class="tab-content px-2 border border-top-0 pt-3 pb-2">
                <div id="tab1" class="tab-pane fade active show p-3">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <span class="font-weight-bold text-uppercase">TIN No</span>
                            <p class="my-1">{{ $renew->tax_agent->tin_no }}</p>
                        </div>
                        <div class="col-md-3 mb-2">
                            <span class="font-weight-bold text-uppercase">Plot No</span>
                            <p class="my-1">{{ $renew->tax_agent->plot_no }}</p>
                        </div>
                        <div class="col-md-3 mb-2">
                            <span class="font-weight-bold text-uppercase">Block</span>
                            <p class="my-1">{{ $renew->tax_agent->block }}</p>
                        </div>
                        <div class="col-md-3 mb-2">
                            <span class="font-weight-bold text-uppercase">Town</span>
                            <p class="my-1">{{ $renew->tax_agent->district->name }}</p>
                        </div>
                        <div class="col-md-3 mb-2">
                            <span class="font-weight-bold text-uppercase">Region</span>
                            <p class="my-1">{{ $renew->tax_agent->region->name }}</p>
                        </div>

                        <div class="col-md-3 mb-2">
                            <span class="font-weight-bold text-uppercase">Request Created At</span>
                            <p class="my-1">{{ date('D, Y-m-d',strtotime($renew->created_at)) }}</p>
                        </div>

                        <div class="col-md-3 mb-2">
                            <span class="font-weight-bold text-uppercase">Request Status</span>
                            <p class="my-1">

                                @if($renew->status == \App\Models\TaxAgentStatus::PENDING)
                                    <span class="badge badge-danger py-1 px-2"
                                          style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%"><i
                                                class="bi bi-clock-history mr-1"></i>Pending</span>

                                @elseif($renew->status == \App\Models\TaxAgentStatus::APPROVED)
                                    <span class="badge badge-success py-1 px-2"
                                          style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%"><i
                                                class="bi bi-check-circle-fill mr-1"></i>Approved</span>
                                @elseif($renew->status == \App\Models\TaxAgentStatus::VERIFIED)
                                    <span class="badge badge-success py-1 px-2"
                                          style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%"><i
                                                class="bi bi-check-circle-fill mr-1"></i>Verified</span>
                                @else
                                    <span class="badge badge-danger py-1 px-2"
                                          style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%"><i
                                                class="bi bi-x-circle-fill mr-1"></i>Rejected</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-3 mb-2">
                            <span class="font-weight-bold text-uppercase">Renew Payment</span>
                            <p>
                                @if ($renew->bill != null)
                                    @if ($renew->bill->status == 'paid')
                                        <span style=" background: #72DC3559; color: #319e0a; font-size: 85%"
                                              class="badge badge-success p-2">Paid</span>
                                    @else
                                        <span style=" background: #dc354559; color: #cf1c2d; font-size: 85%"
                                              class="badge badge-danger p-2">Not Paid</span>
                                    @endif
                                @else
                                    <span style=" background: #dc354559; color: #cf1c2d; font-size: 85%"
                                          class="badge badge-danger p-2">Not Paid</span>
                                @endif
                            </p>
                        </div>



                    </div>
                    @if(empty($fee))
                        <div class="mx-1 p-2">
                            <div class="row py-2 alert alert-danger rounded-0 shadow-sm border-danger">
                                <div class="col-md-6">
                                    <span class="font-weight-bold text-uppercase">Notice</span>
                                    <p class="my-1">Please kindly add renew fee before approving any
                                        request</p>
                                </div>
                                <div class="col-md-6">
                                    <span class="font-weight-bold text-uppercase">Action</span>
                                    <p class="my-1">
                                        <a class="btn btn-primary" href="{{ route('taxagents.fee') }}">
                                            <i class="bi bi-plus-square-fill mr-2"></i>
                                            Add Fee
                                        </a>
                                    </p>
                                </div>

                            </div>
                        </div>
                    @else
                        <livewire:tax-agent.approval.renew.approval-processing
                                modelName='App\Models\RenewTaxAgentRequest' modelId="{{ encrypt($renew->id) }}"/>
                    @endif

                </div>
                <div id="tab2" class="tab-pane fade p-3">
                    <livewire:tax-agent.approval.renew.approval-history-table
                            modelName='App\Models\RenewTaxAgentRequest' modelId="{{ encrypt($renew->id) }}"/>
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

