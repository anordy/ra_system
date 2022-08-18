@extends('layouts.master')

@section('title', 'Tax Consultants ')

@section('content')
    <div class="card">
        <div class="card-header">
            <h6>Tax Consultant Renew Details</h6>
            <div class="card-tools">
                <a class="btn btn-outline-info" href="{{ route('taxagents.renew') }}">Back</a>
            </div>
        </div>
        <div class="card-body">
            @if(empty($fee))
                <div class=" alert alert-danger">
                    <div class="d-flex justify-content-start  align-items-center">
                        <div>
                            <i style="font-size: 30px;" class="bi bi-x-circle mr-1"></i>
                        </div>
                        <div>
                            Please kindly add renew fee before approving any request
                        </div>
                    </div>
                </div>
            @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <span class="font-weight-bold text-uppercase">TIN No</span>
                                <p class="my-1">{{ $agent->tin_no }}</p>
                            </div>
                            <div class="col-md-3 mb-2">
                                <span class="font-weight-bold text-uppercase">Plot No</span>
                                <p class="my-1">{{ $agent->plot_no }}</p>
                            </div>
                            <div class="col-md-3 mb-2">
                                <span class="font-weight-bold text-uppercase">Block</span>
                                <p class="my-1">{{ $agent->block }}</p>
                            </div>
                            <div class="col-md-3 mb-2">
                                <span class="font-weight-bold text-uppercase">Town</span>
                                <p class="my-1">{{ $agent->district->name }}</p>
                            </div>
                            <div class="col-md-3 mb-2">
                                <span class="font-weight-bold text-uppercase">Region</span>
                                <p class="my-1">{{ $agent->region->name }}</p>
                            </div>

                            <div class="col-md-3 mb-2">
                                <span class="font-weight-bold text-uppercase">Request Created At</span>
                                <p class="my-1">{{ date('D, Y-m-d',strtotime($agent->request->created_at)) }}</p>
                            </div>

                            <div class="col-md-3 mb-2">
                                <span class="font-weight-bold text-uppercase">Status</span>
                                <p class="my-1">
                                    @if($agent->request->status == \App\Models\TaxAgentStatus::PENDING)
                                        <span class="badge badge-danger py-1 px-2"
                                              style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%"><i
                                                    class="bi bi-clock-history mr-1"></i>Pending</span>

                                    @elseif($agent->request->status == \App\Models\TaxAgentStatus::APPROVED)
                                        <span class="badge badge-success py-1 px-2"
                                              style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%"><i
                                                    class="bi bi-check-circle-fill mr-1"></i>Approved</span>
                                    @elseif($agent->request->status == \App\Models\TaxAgentStatus::VERIFIED)
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
                                <span class="font-weight-bold text-uppercase">Approved By</span>
                                <p class="my-1">{{ $agent->request->approved_by_id }}</p>
                            </div>

                            <div class="col-md-3 mb-2">
                                <span class="font-weight-bold text-uppercase">Approved At</span>
                                <p class="my-1">{{ date('D, Y-m-d',strtotime($agent->request->approved_at)) }}</p>
                            </div>

                            <div class="col-md-3 mb-2">
                                <span class="font-weight-bold text-uppercase">Approve Comment</span>
                                <p class="my-1">{{ $agent->request->app_true_comment }}</p>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end">
                            <livewire:tax-agent.renew.verify-action :agent="$agent"/>
                        </div>
                    </div>
                </div>

        </div>

    </div>

@endsection
