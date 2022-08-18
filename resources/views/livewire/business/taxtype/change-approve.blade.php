        <div class="card-body pb-0">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Tax Type Change Request Details</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Approval History</a>
            </nav>
            <div class="tab-content px-2 card pt-3 pb-2">
                <div id="tab1" class="tab-pane fade active show">
                    <div class="card p-0 m-0">
                        <div class="card-header text-uppercase font-weight-bold">
                            Change of Tax Type Request for {{ $taxchange->business->name }}
                        </div>
                        <div class="card-body mt-0 p-2 px-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="text-left text-uppercase">Changed by
                                    <strong>{{ $taxchange->taxpayer->full_name }}</strong> <br> on
                                    <strong>{{ $taxchange->created_at->toFormattedDateString() }}</strong></label>
                            </div>

                            <p><strong>Reason for Changing Tax Types: </strong>{{ $taxchange->reason }}</p>
                            <br>
                            <livewire:business.tax-type.tax-type-change-approval-processing
                                modelName='App\Models\BusinessTaxTypeChange' modelId="{{ $taxchange->id }}" />

                        </div>
                    </div>
                </div>
                <div id="tab2" class="tab-pane fade">
                    <livewire:approval.approval-history-table modelName='App\Models\BusinessTaxTypeChange'
                        modelId="{{ $taxchange->id }}" />
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
