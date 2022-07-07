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
            <div class="modal-footer mt-4">
                <a href="{{ route('business.closure') }}" class="btn btn-secondary" data-dismiss="modal">Close</a>
                <button type="button" class="btn btn-danger" wire:click='reject'>Reject Closure</button>
                <button type="button" class="btn btn-success" wire:click='confirm'>Approve & Confirm
                    Closure</button>
            </div>
        </div>

    </div>
