<div>
    @if($schedule && $schedule->job())
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>This task has been scheduled to run at {{ $schedule->time->format('d M, Y H:i') ?? 'N/A' }} |
                Schedule Status: {{ ucfirst($schedule->status ?? 'N/A')  }}</strong>
            <button class="float-right btn btn-danger btn-sm" wire:click="confirmPopUpModal">
                Cancel Task
            </button>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @else

    @endif

    <div class="card">
        <div class="card-header">
            <h5> Report Title: #{{ $task->code }} {{ $task->title ?? 'N/A' }}</h5>
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
                @include('report-register.incident.includes.attachments')
            </div>
        </div>
    </div>


    <ul class="nav nav-tabs" id="myTab" role="tablist">
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
                        <select wire:model.live="priority" class="form-control @error('priority') is-invalid @enderror">
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

                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label>Assigned To</label>
                        <input
                                type="text"
                                class="form-input form-control"
                                placeholder="Search Staffs..."
                                wire:model="query"
                                wire:keydown.escape="resetFields"
                                wire:keydown.tab="resetFields"
                                wire:keydown.arrow-up="decrementHighlight"
                                wire:keydown.arrow-down="incrementHighlight"
                                wire:keydown.enter="selectUser({{ $highlightIndex }})"
                        />

                        <div wire:loading class="absolute z-10 w-full bg-white rounded-t-none shadow-lg list-group">
                            <div class="list-item">Searching...</div>
                        </div>

                        @if(!empty($query))
                            <div class="fixed top-0 bottom-0 left-0 right-0" wire:click="resetFields"></div>

                            <div class="absolute z-10 w-full bg-white rounded-t-none shadow-lg list-group">
                                @if(!empty($users))
                                    @foreach($users as $i => $user)
                                        <a
                                                href="#" wire:click.prevent="selectUser({{ $i }})"
                                                class="list-item {{ $highlightIndex === $i ? 'highlight-dropdown' : '' }}"
                                        >{{ $user['fname'] }} {{ $user['lname'] }}</a>
                                    @endforeach
                                @else
                                    @if(!$staffId)
                                        <div class="list-item">No results!</div>
                                    @endif
                                @endif
                            </div>
                        @endif
                    </div>
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
                        <div class="col-md-1">

                        </div>
                        <div class="form-group col-md-6">
                            <button wire:click="saveComment()" class="btn btn-primary float-right"><i
                                        class="bi bi-chat-left-fill"></i> Save Comment
                            </button>
                        </div>
                    </div>
                    @include('report-register.incident.includes.comments', ['incident' => $task])
                </div>
            </section>
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

