@extends('layouts.master')

@section('title', 'Tax Consultants')

@section('content')
    <div class="d-flex justify-content-between">
        <h6>Registration Details</h6>
        <a class="btn btn-info" href="{{route('taxagents.active')}}">Back</a>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <div class="row my-2">
                <div class="col-md-2 mb-2">
                    <span class="font-weight-bold text-uppercase">TIN No</span>
                    <p class="my-1">{{ $agent->tin_no }}</p>
                </div>
                <div class="col-md-2 mb-2">
                    <span class="font-weight-bold text-uppercase">Plot No</span>
                    <p class="my-1">{{ $agent->plot_no }}</p>
                </div>
                <div class="col-md-2 mb-2">
                    <span class="font-weight-bold text-uppercase">Block</span>
                    <p class="my-1">{{ $agent->block }}</p>
                </div>
                <div class="col-md-2 mb-2">
                    <span class="font-weight-bold text-uppercase">Town</span>
                    <p class="my-1">{{ $agent->town }}</p>
                </div>
                <div class="col-md-2 mb-2">
                    <span class="font-weight-bold text-uppercase">Region</span>
                    <p class="my-1">{{ $agent->region }}</p>
                </div>
                <div class="col-md-2 mb-2">
                    <span class="font-weight-bold text-uppercase">Reference No</span>
                    <p class="my-1">{{ $agent->reference_no }}</p>
                </div>

                <div class="col-md-2 mb-2">
                    <span  class="font-weight-bold text-uppercase">Application Status</span>
                    @if($agent->status == \App\Models\TaxAgentStatus::APPROVED)
                        <p style="font-weight: 900; color: #319e0a; font-size: 85%">Approved</p>
                    @elseif($agent->status == \App\Models\TaxAgentStatus::REJECTED)
                        <p style="font-weight: 900; color: #cf1c2d; font-size: 85%">Rejected</p>
                    @elseif($agent->status == \App\Models\TaxAgentStatus::VERIFIED)
                        <p style="font-weight: 900; color: #319e0a; font-size: 85%">Verified</p>
                    @else
                        <p style="font-weight: 900; color: #cf1c2d; font-size: 85%">Pending</p>
                    @endif
                </div>
                <div class="col-md-2 mb-2">
                    <span  class="font-weight-bold text-uppercase">Application Payment</span>
                    @if($agent->bill->payment->status == 'paid')
                        <p style="font-weight: 900; color: #319e0a; font-size: 85%">Paid</p>
                    @else
                        <p style="font-weight: 900; color: #cf1c2d; font-size: 85%">Not Paid</p>
                    @endif
                </div>

            </div>
            <hr />
            <div class="mt-2">
                <h6>Education</h6>
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>No:</th>
                        <th>Institution</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Examining Body</th>
                        <th>Division/GPA</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($agent->academics as $index=> $academicRecord)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$academicRecord->school_name}}</td>
                            <td>{{$academicRecord->from}}</td>
                            <td>{{$academicRecord->to}}</td>
                            <td>{{$academicRecord->examining_body}}</td>
                            <td>{{$academicRecord->division_id}}</td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>

            <hr />
            <div class="mt-2">
                <h6>Professional</h6>
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>No:</th>
                        <th>Institution</th>
                        <th>Registration No.</th>
                        <th>Passed Section</th>
                        <th>Date passed</th>
                        <th>Remarks</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($agent->professionals as $index=> $agentProfessional)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$agentProfessional->body_name}}</td>
                            <td>{{$agentProfessional->reg_no}}</td>
                            <td>{{$agentProfessional->passed_sections}}</td>
                            <td>{{$agentProfessional->date_passed}}</td>
                            <td>{{$agentProfessional->remarks}}</td>
                        </tr>
                    @endforeach

                    </tbody>

                </table>
            </div>

            <hr />
            <div class="mt-2">
                <h6>Education</h6>
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>No:</th>
                        <th>Institution</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Position Held</th>
                        <th>Description</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($agent->trainings as $index => $agentTraining)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$agentTraining->org_name}}</td>
                            <td>{{$agentTraining->from}}</td>
                            <td>{{$agentTraining->to}}</td>
                            <td>{{$agentTraining->position_held}}</td>
                            <td>{{$agentTraining->description}}</td>
                        </tr>
                    @endforeach

                    </tbody>

                </table>
            </div>

        </div>
    </div>
@endsection