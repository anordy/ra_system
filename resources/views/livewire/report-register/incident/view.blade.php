<div>
    <div class="card">
        <div class="card-header">
            <h5> Report Title: #{{ $incident->code }} {{ $incident->title ?? 'N/A' }}</h5>
            @include('report-register.incident.includes.status', ['status' => $incident->status])
            <div class="card-tools">
                {{ $incident->created_at->format('d M, Y H:i') }}
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Category</span>
                    <p class="my-1"><span class="badge badge-info">{{ $incident->category->name ?? 'N/A' }}</span></p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Sub Category</span>
                    <p class="my-1"><span class="badge badge-info">{{ $incident->subcategory->name ?? 'N/A'  }}</span>
                    </p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Logged On</span>
                    <p class="my-1">{{ $incident->created_at->format('d M, Y H:i') }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Logged By</span>
                    <p class="my-1">{{ $incident->requester_name }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Duration</span>
                    @if(isset($incident->assigned))
                        <p class="my-1">{{ \Carbon\Carbon::create($incident->created_at)->diffInDays($incident->assigned->end_date ?? $incident->created_at) }} Days</p>
                    @else
                        <p class="my-1">Not Assigned</p>
                    @endif
                </div>
                <div class="col-md-12 mb-3">
                    <span class="font-weight-bold text-uppercase">Description</span>
                    <p class="my-1">{{ $incident->description ?? 'N/A'  }}</p>
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
                        <small>For any update on the status a comment must be written</small>
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

                        <div wire:loading wire:target="query" class="absolute z-10 w-full bg-white rounded-t-none shadow-lg list-group">
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
                            <textarea class="form-control" type="text" wire:model.defer="comment"
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
                            <button wire:click="saveComment()" class="btn btn-primary float-right"><i class="bi bi-chat-left-fill"></i> Save Comment</button>
                        </div>
                    </div>
                    @include('report-register.incident.includes.comments')
                </div>
            </section>
        </div>
        <div class="tab-pane fade card p-2" id="audits" role="tabpanel" aria-labelledby="audits-tab">
            @include('report-register.incident.includes.audits')
        </div>
        <div class="tab-pane fade card p-2" id="comments" role="tabpanel" aria-labelledby="comments-tab">
            <span>Assignment History:</span>
            @include('report-register.incident.includes.assignments')
        </div>
    </div>
</div>

