<div class="card-body">
    <nav class="nav nav-tabs mt-0 border-top-0">
        <a href="#tab1" class="nav-item nav-link font-weight-bold active">De-registration Details</a>
        <a href="#tab2" class="nav-item nav-link font-weight-bold">Approval History</a>
    </nav>
    <div class="tab-content px-2 card pt-3 pb-2">
        <div id="tab1" class="tab-pane fade active show">
            <div class="card-body pb-0">
                <div class="row my-2">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Name</span>
                        <p class="my-1">{{ $deregister->business->name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">TIN</span>
                        <p class="my-1">{{ $deregister->business->tin }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Deregisration Type</span>
                        <p class="my-1">
                            @if ($deregister->deregistration_type === 'all')
                                All Locations
                            @else
                                Single Location
                            @endif
                        </p>
                    </div>
                    @if ($deregister->location_id)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Location</span>
                            <p class="my-1">{{ $deregister->location->name }}</p>
                        </div>
                    @endif
                    @if ($deregister->new_headquarter_id ?? null)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">New Head Quarters</span>
                            <p class="my-1">{{ $deregister->headquarters->name ?? '' }}</p>
                        </div>
                    @endif
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Submitted By</span>
                        <p class="my-1">{{ $deregister->taxpayer->fullname }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">De-registration Date</span>
                        <p class="my-1">{{ $deregister->deregistration_date }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">De-registration Status</span>
                        <p class="my-1">{{ $deregister->status }}</p>
                    </div>
                    @if ($deregister->audit)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Tax Audit Status</span>
                        <p class="my-1">{{ $deregister->audit->status }}</p>
                    </div>
                    @endif
                    <div class="col-md-12 mb-3">
                        <span class="font-weight-bold text-uppercase">Reason for De-registration</span>
                        <p class="my-1">{{ $deregister->reason }}</p>
                    </div>
                </div>

                @if ($deregister->deregistration_type === 'all')
                    @livewire('business.deregister.tax-liability', [
                        'business_id' => $deregister->business_id,
                        'location_id' => null,
                        'deregister_id' => $deregister->id
                    ])
                @else
                    @livewire('business.deregister.tax-liability', [
                        'business_id' => null,
                        'location_id' => $deregister->location_id,
                        'deregister_id' => $deregister->id
                    ])
                @endif

                @livewire('business.deregister.deregister-approval-processing', ['modelName' => 'App\Models\BusinessDeregistration', 'modelId' => $deregister->id])
            </div>
        </div>
        <div id="tab2" class="tab-pane fade m-2">
            <livewire:approval.approval-history-table modelName='App\Models\BusinessDeregistration'
                modelId="{{ $deregister->id }}" />
        </div>
    </div>
</div>


@section('scripts')
    <script>
        $(document).ready(function() {
            $(".nav-tabs a").click(function() {
                $(this).tab('show');
            });
        });
    </script>
@endsection
