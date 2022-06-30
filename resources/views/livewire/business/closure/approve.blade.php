    <div class="card">
            <div class="card-header">
                <h5 class="card-title text-uppercase">Approve Temporary Business Closure</h5>
            </div>
            <div class="card-body">
                <div class="row my-2">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Name</span>
                        <p class="my-1">{{ $temporary_business_closure->business->name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Tax Identification No. (TIN)</span>
                        <p class="my-1">{{ $temporary_business_closure->business->tin }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Reg. No.</span>
                        <p class="my-1">{{ $temporary_business_closure->business->reg_no }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Closing Date</span>
                        <p class="my-1">{{ $temporary_business_closure->closing_date }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Opening Date</span>
                        <p class="my-1">{{ $temporary_business_closure->opening_date }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Submitted On</span>
                        <p class="my-1">{{ $temporary_business_closure->created_at->toFormattedDateString() }}</p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <span class="font-weight-bold text-uppercase">Reason</span>
                        <p class="my-1">{{ $temporary_business_closure->reason }}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 d-flex justify-content-center">
                        <a href="../" class="btn btn-danger mr-2">Cancel</a>
                        <button wire:click="approve" class="btn btn-primary">Approve</button>
                    </div>
                </div>
            </div>
          
    </div>
