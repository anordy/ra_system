<div>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Confirm Temporary Closure</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="card border-0 mt-3">
                    <div class="card-body pb-0">
                        <div class="row my-2">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Business Name</span>
                                <p class="my-1">{{ $temp_closure->business->name }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">TIN</span>
                                <p class="my-1">{{ $temp_closure->business->tin }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Submitted By</span>
                                <p class="my-1">{{ $temp_closure->taxpayer->fullname }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Closing Date</span>
                                <p class="my-1">{{ $temp_closure->closing_date }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Opening Date</span>
                                <p class="my-1">{{ $temp_closure->opening_date }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Is Closure Extension</span>
                                <p class="my-1">
                                    @if ($temp_closure->is_extended == 0)
                                        <span class="badge badge-info py-1 px-2">No</span>
                                    @else
                                        <span class="badge badge-success py-1 px-2">Yes</span>  
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <span class="font-weight-bold text-uppercase">Reason for Closure</span>
                                <p class="my-1">{{ $temp_closure->reason }}</p>
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
                <button type="button" class="btn btn-danger" wire:click='reject'>Reject Closure</button>
                <button type="button" class="btn btn-success" wire:click='confirm'>Approve & Confirm Closure</button>
            </div>
        </div>
    </div>
</div>
