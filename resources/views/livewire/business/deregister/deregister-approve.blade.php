<div class="card-body">
    <nav class="nav nav-tabs mt-0 border-top-0">
        <a href="#tab1" class="nav-item nav-link font-weight-bold active">De-registration Details</a>
        <a href="#tab2" class="nav-item nav-link font-weight-bold">Approval History</a>
    </nav>
    <div class="tab-content px-2 card pt-3 pb-2">
        <div id="tab1" class="tab-pane fade active show">
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
                @if ($deregister->status !== 'approved')
                    @livewire('business.deregister.deregister-approval-processing', ['modelName' => 'App\Models\BusinessDeregistration', 'modelId' =>$deregister->id])
                @endif
            </div>
        </div>
        <div id="tab2" class="tab-pane fade">
            <livewire:approval.approval-history-table modelName='App\Models\BusinessDeregistration' modelId="{{ $deregister->id }}" />
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
