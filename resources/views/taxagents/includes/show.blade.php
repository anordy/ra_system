<div>
    <ul style="border-bottom: unset !important;" class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#biz" role="tab" aria-controls="home"
               aria-selected="true">Business & Application Details</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="academic-tab" data-toggle="tab" href="#academic" role="tab"
               aria-controls="profile" aria-selected="false">Academic Details</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="prof-tab" data-toggle="tab" href="#prof" role="tab" aria-controls="contact"
               aria-selected="false">Professional Details</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="training-tab" data-toggle="tab" href="#training" role="tab"
               aria-controls="contact" aria-selected="false">Training Details</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link" id="attachment-tab" data-toggle="tab" href="#attachment" role="tab"
               aria-controls="contact" aria-selected="false">Documents</a>
        </li>
    </ul>
    <div style="border: 1px solid #eaeaea;" class="tab-content" id="myTabContent">

        <div class="tab-pane p-2 show active" id="biz" role="tabpanel" aria-labelledby="biz-tab">
            <div class="row pt-3">
                <div class="col-md-3 mb-2">
                    <span class="font-weight-bold text-uppercase">Tax Payer Name</span>
                    <p class="my-1">{{ $agent->taxpayer->first_name}} {{$agent->taxpayer->middle_name}} {{$agent->taxpayer->last_name}}</p>
                </div>
                <div class="col-md-3 mb-2">
                    <span class="font-weight-bold text-uppercase">Tax Payer Reference No</span>
                    <p class="my-1">{{ $agent->taxpayer->reference_no }}</p>
                </div>
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
                    <span class="font-weight-bold text-uppercase">Consultation Reference No</span>
                    @if($agent->status == \App\Models\TaxAgentStatus::APPROVED)
                        <p class="my-1">{{ $agent->reference_no }}</p>
                    @else
                        <p class="badge badge-danger px-2"style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%"><i class="bi bi-clock-history mr-1"></i>Pending</p>
                    @endif
                </div>

                <div class="col-md-3 mb-2">
                    <span class="font-weight-bold text-uppercase">Application Type</span>

                    @if ($agent->is_first_application == 1)
                        <p class="my-1">Registration</p>
                    @else
                        <p class="my-1">Renewal</p>
                    @endif
                </div>

                <div class="col-md-3 mb-2">
                    <span class="font-weight-bold text-uppercase">Application Status</span>
                    @if($agent->status == \App\Models\TaxAgentStatus::PENDING)
                        <p class="badge badge-danger py-1 px-2"
                              style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%"><i
                                    class="bi bi-clock-history mr-1"></i>Pending</p>

                    @elseif($agent->status == \App\Models\TaxAgentStatus::APPROVED)
                        <p class="badge badge-success py-1 px-2"
                              style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%"><i
                                    class="bi bi-check-circle-fill mr-1"></i>Approved</p>
                    @elseif($agent->status == \App\Models\TaxAgentStatus::VERIFIED)
                        <p class="badge badge-success py-1 px-2"
                              style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%"><i
                                    class="bi bi-check-circle-fill mr-1"></i>Verified</p>
                    @else
                        <p class="badge badge-danger py-1 px-2"
                              style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%"><i
                                    class="bi bi-x-circle-fill mr-1"></i>Rejected</p>
                    @endif

                </div>
                <div class="col-md-3 mb-2">
                    <span class="font-weight-bold text-uppercase">Registration Payment</span>

                    <p>@if(!empty($agent->bill))
                            @if ($agent->bill->status == \App\Models\PaymentStatus::PAID)
                                <span class="badge badge-success py-1 px-2"
                                      style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%"><i
                                            class="bi bi-check-circle-fill mr-1"></i>Paid</span>
                            @elseif($agent->bill->status == \App\Models\PaymentStatus::PENDING)
                                <span class="badge badge-danger py-1 px-2"
                                      style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%"><i
                                            class="bi bi-clock-history mr-1"></i>Not Paid</span>
                    @elseif($agent->bill->status == \App\Models\PaymentStatus::PARTIALLY)
                        <span style="font-weight: 900; color: #319e0a; font-size: 85%">Partially Paid</span>
                    @elseif($agent->bill->status == \App\Models\PaymentStatus::CANCELLED)
                        <span class="badge badge-danger py-1 px-2"
                              style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%"><i
                                    class="bi bi-x-circle-fill mr-1"></i>Canceled</span>
                    @else
                        <span class="badge badge-danger py-1 px-2"
                              style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%"><i
                                    class="bi bi-x-circle-fill mr-1"></i>Failed</span>
                    @endif
                    @else
                        <span class="badge badge-danger py-1 px-2"
                              style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%"><i
                                    class="bi bi-x-circle-fill mr-1"></i>Not Paid</span>
                        @endif
                        </p>

                </div>

                @if(!empty($agent->request))
                <div class="col-md-3 mb-2">
                        <span class="font-weight-bold text-uppercase">Renew Payment</span>
                        <p>
                            @if ($agent->request->bill != null)
                                @if ($agent->request->bill->status == 'paid')
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
                @endif
            </div>

            <div class="card">
                <div class="card-header">Tax Consultant Approval Levels </div>
                <div class="card">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Verified By</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Verification</td>
                            <td>{{\App\Http\Controllers\TaxAgents\TaxAgentController::getUser($agent->verifier_id)}}</td>
                            <td>{{$agent->verifier_true_comment}}</td>
                            <td>{{date('D, Y-m-d', strtotime($agent->verified_at))}}</td>
                        </tr>
                        @if($agent->approver_id != null)
                        <tr>
                            <td>Approval</td>
                            <td>{{\App\Http\Controllers\TaxAgents\TaxAgentController::getUser($agent->approver_id)}}</td>
                            <td>{{$agent->app_true_comment}}</td>
                            <td>{{date('D, Y-m-d', strtotime($agent->approved_at))}}</td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane p-2" id="academic" role="tabpanel" aria-labelledby="academic-tab">
            <table class="table table-striped table-bordered ">
                <thead>
                <tr>
                    <th>No:</th>
                    <th>Institution</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Education Level</th>
                    <th>Program</th>
                    <th>Certificate</th>
                    <th>Transcript</th>
                </tr>
                </thead>

                <tbody>

                @foreach($agent->academics as $index=> $academicRecord)
                    <tr>
                        <td>{{$index + 1}}</td>
                        <td>{{$academicRecord->school_name}}</td>
                        <td>{{$academicRecord->from}}</td>
                        <td>{{$academicRecord->to}}</td>
                        <td>{{\App\Models\EducationLevel::find($academicRecord->education_level_id)->name}}</td>
                        <td>{{$academicRecord->program}}</td>
                        <td>
                            <a class="file-item" target="_blank"
                               href="{{ route('agent.file', [$agent->id, 'academic_certificate']) }}">
                                <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                <div style="font-weight: 500;" class="ml-1">
                                    {{\App\Models\EducationLevel::find($academicRecord->education_level_id)->name}}
                                </div>
                            </a>
                        </td>
                        <td>
                            <a class="file-item" target="_blank"
                               href="{{ route('agent.file', [$agent->id, 'academic_transcript']) }}">
                                <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                <div style="font-weight: 500;" class="ml-1">
                                    {{\App\Models\EducationLevel::find($academicRecord->education_level_id)->name}}
                                </div>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
        <div class="tab-pane p-2" id="prof" role="tabpanel" aria-labelledby="prof-tab">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>No:</th>
                    <th>Institution</th>
                    <th>Registration No.</th>
                    <th>Passed Section</th>
                    <th>Date passed</th>
                    <th>Remarks</th>
                    <th>Attachment</th>
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
                        <td>
                            <a class="file-item" target="_blank"
                               href="{{ route('agent.file', [$agent->id, 'pro_certificate']) }}">
                                <i class="bi bi-file-earmark-pdf-fill px-2"
                                   style="font-size: x-large"></i>
                                <div style="font-weight: 500;" class="ml-1">
                                    {{$agentProfessional->body_name}}
                                </div>
                            </a>
                        </td>
                    </tr>
                @endforeach

                </tbody>

            </table>
        </div>
        <div class="tab-pane p-2" id="training" role="tabpanel" aria-labelledby="training-tab">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>No:</th>
                    <th>Institution</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Position Held</th>
                    <th>Description</th>
                    <th>Attachment</th>
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
                        <td>
                            <a class="file-item" target="_blank"
                               href="{{ route('agent.file', [$agent->id, 'tra_certificate']) }}">
                                <i class="bi bi-file-earmark-pdf-fill px-2"
                                   style="font-size: x-large"></i>
                                <div style="font-weight: 500;" class="ml-1">
                                    {{$agentTraining->org_name}}
                                </div>
                            </a>
                        </td>
                    </tr>
                @endforeach

                </tbody>

            </table>
        </div>

        <div class="tab-pane p-2" id="attachment" role="tabpanel" aria-labelledby="attachment-tab">
            <div class="">

                <div class="disp-Info text-center">
                    Attachments
                </div>

                <div class="row pt-2 mt-2">
                    <div class="col-md-3">
                        <a class="file-item" target="_blank"
                           href="{{ route('agent.file', [$agent->id, 'tin_certificate']) }}">
                            <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                            <div style="font-weight: 500;" class="ml-1">
                                TIN Certificate
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a class="file-item" target="_blank"
                           href="{{ route('agent.file', [$agent->id, 'csv']) }}">
                            <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                            <div style="font-weight: 500;" class="ml-1">
                                CV Document
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a class="file-item" target="_blank"
                           href="{{ route('agent.file', [$agent->id, 'passport_photo']) }}">
                            <i class="bi bi-image-fill px-2" style="font-size: x-large"></i>
                            <div style="font-weight: 500;" class="ml-1">
                                Passport Size
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        @if($agent->emp_letter != null)
                            <a class="file-item" target="_blank"
                               href="{{ route('agent.file', [$agent->id, 'emp_letter']) }}">
                                <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                <div style="font-weight: 500;" class="ml-1">
                                    Employer Letter
                                </div>
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>