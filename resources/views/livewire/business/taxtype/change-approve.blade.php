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
                        <div class="card-body mt-0 p-2">
                
                            <div class="row m-2">
                                <div class="col-md-6">
                                    <div class="text-uppercase font-weight-bold">
                                        Previous Tax Types
                                    </div>
                
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Tax Type</span>
                                        <p class="my-1">
                                            @foreach (json_decode($taxchange->old_taxtype) as $type)
                                                {{ $type->name }}<br>
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-uppercase font-weight-bold">
                                        New Tax Types
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <span class="font-weight-bold text-uppercase">Tax Type</span>
                                        <p class="my-1">
                                            @foreach (json_decode($taxchange->new_taxtype) as $type)
                                            {{ $this->getTaxName($type->tax_type_id) }}<br>
                                        @endforeach
                                        </p>
                                    </div>
                                </div>
                
                
                                <hr style="margin-top: -16px" class="mx-3" />
                            </div>
                            <livewire:business.tax-type.tax-type-change-approval-processing modelName='App\Models\BusinessTaxTypeChange'
                                modelId="{{ $taxchange->id }}" />
                
                        </div>
                    </div>
                </div>
                <div id="tab2" class="tab-pane fade">
                    <livewire:approval.approval-history-table modelName='App\Models\BusinessTaxTypeChange' modelId="{{ $taxchange->id }}" />
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
