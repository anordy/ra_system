<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Edit Investigation members</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row px-3">
                    <div class="col-lg-6 form-group">
                        <label class="font-weight-bold">Team Leader</label>
                        <select class="form-control @error("teamLeader") is-invalid @enderror" wire:model="teamLeader">
                            @foreach ($staffs as $row)
                                <option value="{{ $row->id }}" @if($row->id == $teamLeader) selected @endif>{{ $row->full_name }}</option>
                            @endforeach
                        </select>
                        @error("teamLeader")
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 form-group">
                        <label class="font-weight-bold">Team Member</label>
                        @foreach($teamMembers as $key => $teamMember)
                            <div class="row">
                                <div class="col">
                                    <select class="form-control @error("teamMembers.{{ $key }}") is-invalid @enderror" wire:model="teamMembers.{{ $key }}">
                                        <option value='' disabled selected>Select</option>
                                        @foreach ($staffs as $row)
                                            <option value="{{ $row->id }}">{{ $row->full_name }}</option>
                                        @endforeach
                                    </select>
                                    @error("teamMembers.{{ $key }}")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click="confirmPopUpModal" wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Save Members
                </button>
            </div>
        </div>
    </div>
</div>