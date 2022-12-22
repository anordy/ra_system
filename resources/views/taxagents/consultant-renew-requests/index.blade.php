@extends('layouts.master')

@section('title', 'Tax Consultants Renew Requests')

@section('content')
    <div class="card">
        <div class="card-header">
            <h6>Renew Request from {{$consultant ?? ''}}</h6>
            <div class="card-tools">
                <a class="btn btn-info" href="{{ route('taxagents.active-show', $id) }}">
                    <i class="bi bi-arrow-return-left mr-2"></i>
                    Back
                </a>
            </div>
        </div>

        <div class="card-body">
            @if(count($requests) > 0)
                @foreach($requests as $request)
                    <div class="card p-0 m-0">
                        <div class="card-header">
                            <h6>Renew Request for {{ date('Y',strtotime($request->created_at)) }}</h6>
                        </div>
                        <div class="card-body mt-0 p-2">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <span class="font-weight-bold text-uppercase">Request Created At</span>
                                    <p class="my-1">{{ date('D, d-m-Y',strtotime($request->created_at)) }}</p>
                                </div>

                                <div class="col-md-3 mb-2">
                                    <span class="font-weight-bold text-uppercase">Request Status</span>
                                    <p class="my-1">

                                        @if($request->status == \App\Models\TaxAgentStatus::PENDING)
                                            <span class="badge badge-danger py-1 px-2"
                                                  style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%"><i
                                                        class="bi bi-clock-history mr-1"></i>Pending</span>

                                        @elseif($request->status == \App\Models\TaxAgentStatus::APPROVED)
                                            <span class="badge badge-success py-1 px-2"
                                                  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%"><i
                                                        class="bi bi-check-circle-fill mr-1"></i>Approved</span>
                                        @elseif($request->status == \App\Models\TaxAgentStatus::VERIFIED)
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
                                        @if ($request->bill != null)
                                            @if ($request->bill->status == 'paid')
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

                                <div class="col-md-3 mb-2">
                                    <span class="font-weight-bold text-uppercase">Request Expire Date</span>
                                    <p class="my-1">{{ date('D, d-m-Y',strtotime($request->renew_expire_date)) }}</p>
                                </div>

                            </div>
                            <hr>

                            <div>
                                <h6 class="text-capitalize">Approval history</h6>
                                <livewire:tax-agent.approval.registration.approval-history-table
                                        modelName='App\Models\RenewTaxAgentRequest'
                                        modelId="{{ encrypt($request->id) }}"/>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info text-center">
                    No renew requests from the selected taxpayer
                </div>
            @endif
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
