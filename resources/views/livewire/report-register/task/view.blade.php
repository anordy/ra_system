<div>
    @if($schedule && $schedule->job())
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>This task has been scheduled to run at {{ $schedule->time->format('d M, Y H:i') ?? 'N/A' }} |
                Schedule Status: {{ ucfirst($schedule->status ?? 'N/A')  }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5> Task Title: #{{ $task->code }} {{ $task->title ?? 'N/A' }}</h5>
            @include('report-register.task.includes.status', ['status' => $task->status])
            <div class="card-tools">
                {{ $task->created_at->format('d M, Y H:i') }}
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Created On</span>
                    <p class="my-1">{{ $task->created_at->format('d M, Y H:i') }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Created By</span>
                    <p class="my-1">{{ $task->requester_name }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Start Date</span>
                    <p class="my-1">{{ $task->start_date->format('d M, Y H:i') }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Is Scheduled</span>
                    <p class="my-1">{{ $task->is_scheduled ? 'Yes' : 'No' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Duration</span>
                    <p class="my-1">{{ \Carbon\Carbon::create($task->start_date)->diffInDays($task->assigned->end_date) }}
                        Days</p>
                </div>
                <div class="col-md-12 mb-3">
                    <span class="font-weight-bold text-uppercase">Description</span>
                    <p class="my-1">{{ $task->description ?? 'N/A'  }}</p>
                </div>
                @include('report-register.incident.includes.attachments', ['incident' => $task])
            </div>
        </div>
    </div>


    <ul class="nav nav-tabs" id="myTab">
        <li class="nav-item">
            <a class="nav-link active" id="assignment-tab" data-toggle="tab" href="#assignment" role="tab"
               aria-controls="assignment"
               aria-selected="false">Assignment Configuration</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="audits-tab" data-toggle="tab" href="#audits" role="tab" aria-controls="audits"
               aria-selected="false">Audits Information</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="comments-tab" data-toggle="tab" href="#comments" role="tab" aria-controls="comments"
               aria-selected="false">Assignment History</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active card p-2" id="assignment" role="tabpanel"
             aria-labelledby="assignment-tab">
            @if($task->status != \App\Enum\ReportRegister\RgTaskStatus::CLOSED)
            <div class="row m-4">
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label>Set Status</label>
                        <select wire:model.live="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="">Select Status</option>
                            @foreach ($statuses ?? [] as $status)
                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                        @error('status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label>Set Priority</label>
                        <select wire:model.live="priority" disabled class="form-control @error('priority') is-invalid @enderror">
                            <option value="">Select Priority</option>
                            @foreach ($priorities ?? [] as $priority)
                                <option value="{{ $priority }}">{{ ucfirst($priority) }}</option>
                            @endforeach
                        </select>
                        @error('priority')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group col-lg-12">
                    <label class="">Assigned To * </label>
                    <select class="form-control min-height-150" wire:model="staffId" multiple>
                        <option disabled>Choose option</option>
                        @foreach ($users ?? [] as $user)
                            <option value="{{ $user->id }}">{{ $user->fullname }}</option>
                        @endforeach
                    </select>
                    <small>Please press CTRL key on your keyboard to select more than one assignee</small>
                    <br>
                    @error('staffId')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>
            <hr>
            <section class="content-item" id="comment">
                <div class="container">
                    <h6>New Comment</h6>
                    <div class="row pt-2">
                        <div class="col-md-1">
                            <img class="rounded-circle bg-secondary p-2"
                                 alt="{{ \Illuminate\Support\Facades\Auth::user()->initials }}">
                        </div>
                        <div class="form-group col-md-6">
                            <textarea class="form-control" type="text" wire:model="comment"
                                      placeholder="Your comment" rows="4" required></textarea>
                            @error('comment')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-md-1"></div>
                        <div class="form-group col-md-6">
                            <button wire:click="saveComment()" class="btn btn-primary float-right"><i
                                        class="bi bi-chat-left-fill"></i> Save Comment
                            </button>
                        </div>
                    </div>
                    @include('report-register.incident.includes.comments', ['incident' => $task])
                </div>
            </section>
            @else
                <p>No Actions Available as this Task has been Closed</p>
            @endif

        </div>
        <div class="tab-pane fade card p-2" id="audits" role="tabpanel" aria-labelledby="audits-tab">
            @include('report-register.incident.includes.audits', ['incident' => $task])
        </div>
        <div class="tab-pane fade card p-2" id="comments" role="tabpanel" aria-labelledby="comments-tab">
            <span>Assignment History:</span>
            @include('report-register.incident.includes.assignments', ['incident' => $task])
        </div>
    </div>
</div>

