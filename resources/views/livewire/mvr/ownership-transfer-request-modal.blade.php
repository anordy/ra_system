<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Ownership Transfer Request</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Transfer Category</label>
                        <select class="form-control" wire:model.lazy="category_id" id="category_id">
                            <option value="" selected>Choose option</option>
                            @foreach (\App\Models\MvrTransferCategory::query()->get() as $row)
                                <option value="{{$row->id}}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Reason</label>
                        <select class="form-control" wire:model.lazy="reason_id" id="reason_id">
                            <option value="" selected>Choose option</option>
                            @foreach (\App\Models\MvrOwnershipTransferReason::query()->get() as $row)
                                <option value="{{$row->id}}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        @error('reason_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                @if(\App\Models\MvrOwnershipTransferReason::query()->firstOrCreate(['name'=>\App\Models\MvrOwnershipTransferReason::TRANSFER_REASON_SOLD])->id == $reason_id)
                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-12">
                            <label class="control-label">Date Sold</label>
                            <input type="date" class="form-control" wire:model.lazy="sale_date" id="sale_date">
                            @error('sale_date')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Market Value</label>
                        <input type="number" class="form-control" wire:model.lazy="market_value" id="market_value">
                        @error('market_value')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Date Delivered to new owner</label>
                        <input type="date" class="form-control" wire:model.lazy="date_delivered" id="date_delivered">
                        @error('date_delivered')
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
                        <label class="control-label">New Owner Z-Number</label>
                        <input type="text" class="form-control" wire:model.lazy="owner_z_number" id="owner_z_number">
                        @error('owner_z_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-sm btn-primary" wire:click='ownerLookup'>Lookup</button>
                            </div>
                            <div class="col-6">
                                <span class="p-1">
                                    <strong>New Owner Name: </strong><span class="text-center">{{$owner_name??'Not available'}}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Agent Z-Number</label>
                        <input type="text" class="form-control" wire:model.lazy="agent_z_number" id="agent_z_number">
                        @error('agent_z_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-sm btn-primary" wire:click='agentLookup'>Lookup</button>
                            </div>
                            <div class="col-6">
                                <span class="p-1">
                                    <strong>Agent Name: </strong><span class="text-center">{{$agent_name??'Not available'}}</span>
                                </span>
                            </div>
                        </div>
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
