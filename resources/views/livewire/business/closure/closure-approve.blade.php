        <div class="card-body pb-0">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Temporary Closure Details</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Approval History</a>
            </nav>
            <div class="tab-content px-2 card pt-3 pb-2">
                <div id="tab1" class="tab-pane fade active show">
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
                    @livewire('business.closure.closure-approval-processing', ['modelName' => 'App\Models\BusinessTempClosure', 'modelId' => $temp_closure->id])
                </div>
                <div id="tab2" class="tab-pane fade">
                    <livewire:approval.approval-history-table modelName='App\Models\BusinessTempClosure' modelId="{{ $temp_closure->id }}" />
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
