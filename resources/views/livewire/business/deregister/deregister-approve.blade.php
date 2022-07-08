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
        @livewire('business.deregister.deregister-approval-processing', ['modelName' => 'App\Models\BusinessDeregistration', 'modelId' =>$deregister->id])
    </div>
</div>