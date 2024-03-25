<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">De-registration Request</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Reason</label>
                        <select class="form-control" wire:model.lazy="reason_id" id="reason_id">
                            <option value="" selected>Choose option</option>
                            @foreach (\App\Models\MvrDeRegistrationReason::query()->get() as $row)
                                <option value="{{$row->id}}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        @error('reason_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Description</label>
                        <textarea type="text" class="form-control" wire:model.lazy="description" id="description"></textarea>
                        @error('description')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Date Received</label>
                        <input type="date" class="form-control" wire:model.lazy="date_received" id="date_received">
                        @error('date_received')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Agent Reference Number</label>
                        <input type="text" class="form-control" wire:model.lazy="agent_z_number" id="agent_z_number">
                        @error('agent_z_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <button wire:click="agentLookup" wire:loading.attr="disabled" class="btn btn-primary">
                                    <div wire:loading wire:target="submit">
                                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>Lookup
                                </button>
                            </div>
                            <div class="col-6">
                                <span class="p-1">
                                    <strong>Agent Name: </strong><span class="text-center">{{$agent_name??'Not available'}}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Inspection Report</label>
                        <input type="file" class="form-control" wire:model.lazy="inspection_report" id="inspection_report">
                        @error('inspection_report')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="text-secondary small">
                        <span class="font-weight-bold">
                            {{ __('Note') }}:
                        </span>
                            <span class="">
                            {{ __('Uploaded Documents must be less than 3  MB in size') }}
                        </span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button wire:click="submit" wire:loading.attr="disabled" class="btn btn-primary">
                    <div wire:loading wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Submit
                </button>
            </div>
        </div>
    </div>
</div>
