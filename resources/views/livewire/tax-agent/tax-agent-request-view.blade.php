<div>
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase text-center">Tax agent details </h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="col-md-12">
                        <h6 class="text-center">Business</h6>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Plot No.</th>
                                <th>TIN No.</th>
                                <th>Block</th>
                                <th>Town</th>
                                <th>Region</th>
                                <th>Reference No</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($agents as $index=> $agent)
                            <tr>
                                <td>{{$index + 1}}</td>
                                <td>{{$agent->tin_no}}</td>
                                <td>{{$agent->plot_no}}</td>
                                <td>{{$agent->block}}</td>
                                <td>{{$agent->town}}</td>
                                <td>{{$agent->region}}</td>
                                <td>{{$agent->reference_no}}</td>
                                <td>
                                @if($agent->is_verified == 0)
                                    <p><span class="badge badge-danger p-2">Pending</span></p>

                                    @elseif($agent->is_verified == 2)
                                        <p><span class="badge badge-danger p-2">Rejected</span></p>
                                @else
                                    <p><span class="badge badge-success p-2">Verified</span></p>
                                @endif
                                </td>

                            </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-12">
                        <h6 class="text-center">Academics</h6>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>School Name</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Examining Body</th>
                                <th>Division</th>
                                <th>Created At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($academics as $index=> $academic)
                                <tr>
                                    <td>{{$index + 1}}</td>
                                    <td>{{$academic->school_name}}</td>
                                    <td>{{$academic->from}}</td>
                                    <td>{{$academic->to}}</td>
                                    <td>{{$academic->examining_body}}</td>
                                    <td>{{$academic->division_id}}</td>
                                    <td>{{$academic->created_at}}</td>


                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-12">
                        <h6 class="text-center">Professional</h6>
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
                            @foreach($professionals as $index=> $proo)
                                <tr>
                                    <td>{{$index + 1}}</td>
                                    <td>{{$proo->body_name}}</td>
                                    <td>{{$proo->reg_no}}</td>
                                    <td>{{$proo->passed_sections}}</td>
                                    <td>{{$proo->date_passed}}</td>
                                    <td>{{$proo->remarks}}</td>
                                </tr>
                            @endforeach

                            </tbody>

                        </table>
                    </div>

                    <div class="col-md-12">
                        <h6 class="text-center">Trainings</h6>
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
                            @foreach($trainings as $index => $row)
                                <tr>
                                    <td>{{$index + 1}}</td>
                                    <td>{{$row->org_name}}</td>
                                    <td>{{$row->from}}</td>
                                    <td>{{$row->to}}</td>
                                    <td>{{$row->position_held}}</td>
                                    <td>{{$row->description}}</td>
                                </tr>
                            @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>