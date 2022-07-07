<div>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Confirm Business De-registration</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="card border-0 mt-3">
                    <div class="card-body pb-0">
                        <div class="row my-2">
                            <div class="col-md-6 mb-3">
                                <span class="font-weight-bold text-uppercase">Business Name</span>
                                <p class="my-1">{{ $deregister->business->name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="font-weight-bold text-uppercase">TIN</span>
                                <p class="my-1">{{ $deregister->business->tin }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="font-weight-bold text-uppercase">Submitted By</span>
                                <p class="my-1">{{ $deregister->taxpayer->fullname }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="font-weight-bold text-uppercase">De-registration Date</span>
                                <p class="my-1">{{ $deregister->deregistration_date }}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <span class="font-weight-bold text-uppercase">Reason for De-registration</span>
                                <p class="my-1">{{ $deregister->reason }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Comments</label>
                        <input type="text" class="form-control" wire:model.lazy="name" id="name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" wire:click='reject'>Reject De-registration</button>
                <button type="button" class="btn btn-success" wire:click='approve'>Approve & Confirm De-registration</button>
            </div>
        </div>
    </div>
</div>
