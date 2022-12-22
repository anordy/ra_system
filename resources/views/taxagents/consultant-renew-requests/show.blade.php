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
{{--            @if(empty($fee))--}}
{{--                <div class=" alert alert-danger">--}}
{{--                    <div class="d-flex justify-content-start  align-items-center">--}}
{{--                        <div>--}}
{{--                            <i style="font-size: 30px;" class="bi bi-x-circle mr-1"></i>--}}
{{--                        </div>--}}
{{--                        <div>--}}
{{--                            Please kindly add renew fee before approving any request--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--            @else--}}
{{--                @if($renew->status == \App\Models\TaxAgentStatus::PENDING)--}}
{{--                    <div class="d-flex justify-content-end">--}}
{{--                        <livewire:tax-agent.renew.verify-action :renew="$renew"/>--}}
{{--                    </div>--}}
{{--                @endif--}}

{{--                @if($renew->status == \App\Models\TaxAgentStatus::VERIFIED)--}}

{{--                    @if ($renew->bill != null)--}}
{{--                        @if ($renew->bill->status == 'paid')--}}
{{--                            <div class="d-flex justify-content-end">--}}
{{--                                <livewire:tax-agent.renew.approve-action :renew="$renew"/>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    @endif--}}
{{--                @endif--}}
{{--            @endif--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-3 mb-2">--}}
{{--                            <span class="font-weight-bold text-uppercase">TIN No</span>--}}
{{--                            <p class="my-1">{{ $renew->tax_agent->tin_no }}</p>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-3 mb-2">--}}
{{--                            <span class="font-weight-bold text-uppercase">Plot No</span>--}}
{{--                            <p class="my-1">{{ $renew->tax_agent->plot_no }}</p>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-3 mb-2">--}}
{{--                            <span class="font-weight-bold text-uppercase">Block</span>--}}
{{--                            <p class="my-1">{{ $renew->tax_agent->block }}</p>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-3 mb-2">--}}
{{--                            <span class="font-weight-bold text-uppercase">Town</span>--}}
{{--                            <p class="my-1">{{ $renew->tax_agent->district->name }}</p>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-3 mb-2">--}}
{{--                            <span class="font-weight-bold text-uppercase">Region</span>--}}
{{--                            <p class="my-1">{{ $renew->tax_agent->region->name }}</p>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-3 mb-2">--}}
{{--                            <span class="font-weight-bold text-uppercase">Request Created At</span>--}}
{{--                            <p class="my-1">{{ date('D, Y-m-d',strtotime($renew->created_at)) }}</p>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-3 mb-2">--}}
{{--                            <span class="font-weight-bold text-uppercase">Request Status</span>--}}
{{--                            <p class="my-1">--}}

{{--                                @if($renew->status == \App\Models\TaxAgentStatus::PENDING)--}}
{{--                                    <span class="badge badge-danger py-1 px-2"--}}
{{--                                          style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%"><i--}}
{{--                                                class="bi bi-clock-history mr-1"></i>Pending</span>--}}

{{--                                @elseif($renew->status == \App\Models\TaxAgentStatus::APPROVED)--}}
{{--                                    <span class="badge badge-success py-1 px-2"--}}
{{--                                          style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%"><i--}}
{{--                                                class="bi bi-check-circle-fill mr-1"></i>Approved</span>--}}
{{--                                @elseif($renew->status == \App\Models\TaxAgentStatus::VERIFIED)--}}
{{--                                    <span class="badge badge-success py-1 px-2"--}}
{{--                                          style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%"><i--}}
{{--                                                class="bi bi-check-circle-fill mr-1"></i>Verified</span>--}}
{{--                                @else--}}
{{--                                    <span class="badge badge-danger py-1 px-2"--}}
{{--                                          style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%"><i--}}
{{--                                                class="bi bi-x-circle-fill mr-1"></i>Rejected</span>--}}
{{--                                @endif--}}
{{--                            </p>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-3 mb-2">--}}
{{--                            <span class="font-weight-bold text-uppercase">Renew Payment</span>--}}
{{--                            <p>--}}
{{--                                @if ($renew->bill != null)--}}
{{--                                    @if ($renew->bill->status == 'paid')--}}
{{--                                        <span style=" background: #72DC3559; color: #319e0a; font-size: 85%"--}}
{{--                                              class="badge badge-success p-2">Paid</span>--}}
{{--                                    @else--}}
{{--                                        <span style=" background: #dc354559; color: #cf1c2d; font-size: 85%"--}}
{{--                                              class="badge badge-danger p-2">Not Paid</span>--}}
{{--                                    @endif--}}
{{--                                @else--}}
{{--                                    <span style=" background: #dc354559; color: #cf1c2d; font-size: 85%"--}}
{{--                                          class="badge badge-danger p-2">Not Paid</span>--}}
{{--                                @endif--}}
{{--                            </p>--}}
{{--                        </div>--}}

{{--                        @if(!empty($renew->approved_by_id))--}}
{{--                            <div class="col-md-3 mb-2">--}}
{{--                                <span class="font-weight-bold text-uppercase">Approved By</span>--}}
{{--                                <p class="my-1">{{$renew->approved_by->fname}} {{$renew->approved_by->lname}}</p>--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        @if(!empty($renew->approved_at))--}}
{{--                            <div class="col-md-3 mb-2">--}}
{{--                                <span class="font-weight-bold text-uppercase">Approved At</span>--}}
{{--                                <p class="my-1">{{$renew->approved_at}} </p>--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        @if(!empty($renew->app_true_comment))--}}
{{--                            <div class="col-md-3 mb-2">--}}
{{--                                <span class="font-weight-bold text-uppercase">Approval Comment</span>--}}
{{--                                <p class="my-1">{{$renew->app_true_comment}} </p>--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        @if(!empty($renew->rejected_by_id))--}}
{{--                            <div class="col-md-3 mb-2">--}}
{{--                                <span class="font-weight-bold text-uppercase">Rejected By</span>--}}
{{--                                <p class="my-1">{{$renew->rejected_by->fname}} {{$renew->approved_by->lname}}</p>--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        @if(!empty($renew->rejected_at))--}}
{{--                            <div class="col-md-3 mb-2">--}}
{{--                                <span class="font-weight-bold text-uppercase">Rejected At</span>--}}
{{--                                <p class="my-1">{{$renew->rejected_at}} </p>--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        @if(!empty($renew->app_reject_comment))--}}
{{--                            <div class="col-md-3 mb-2">--}}
{{--                                <span class="font-weight-bold text-uppercase">Comment Reject</span>--}}
{{--                                <p class="my-1">{{ $renew->app_reject_comment }}</p>--}}
{{--                            </div>--}}
{{--                        @endif--}}


{{--                    </div>--}}

{{--                </div>--}}
{{--            </div>--}}

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

                        @if(!empty($renew->approved_by_id))
                            <div class="col-md-3 mb-2">
                                <span class="font-weight-bold text-uppercase">Approved By</span>
                                <p class="my-1">{{$renew->approved_by->fname}} {{$renew->approved_by->lname}}</p>
                            </div>
                        @endif

                        @if(!empty($renew->approved_at))
                            <div class="col-md-3 mb-2">
                                <span class="font-weight-bold text-uppercase">Approved At</span>
                                <p class="my-1">{{$renew->approved_at}} </p>
                            </div>
                        @endif

                        @if(!empty($renew->app_true_comment))
                            <div class="col-md-3 mb-2">
                                <span class="font-weight-bold text-uppercase">Approval Comment</span>
                                <p class="my-1">{{$renew->app_true_comment}} </p>
                            </div>
                        @endif

                        @if(!empty($renew->rejected_by_id))
                            <div class="col-md-3 mb-2">
                                <span class="font-weight-bold text-uppercase">Rejected By</span>
                                <p class="my-1">{{$renew->rejected_by->fname}} {{$renew->approved_by->lname}}</p>
                            </div>
                        @endif

                        @if(!empty($renew->rejected_at))
                            <div class="col-md-3 mb-2">
                                <span class="font-weight-bold text-uppercase">Rejected At</span>
                                <p class="my-1">{{$renew->rejected_at}} </p>
                            </div>
                        @endif

                        @if(!empty($renew->app_reject_comment))
                            <div class="col-md-3 mb-2">
                                <span class="font-weight-bold text-uppercase">Comment Reject</span>
                                <p class="my-1">{{ $renew->app_reject_comment }}</p>
                            </div>
                        @endif


                    </div>
                    <livewire:tax-agent.approval.renew.approval-processing
                            modelName='App\Models\RenewTaxAgentRequest' modelId="{{ encrypt($renew->id) }}"/>
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

