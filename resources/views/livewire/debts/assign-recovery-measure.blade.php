<div class="card-body pb-0">
    <nav class="nav nav-tabs mt-0 border-top-0">
        <a href="#tab1" class="nav-item nav-link font-weight-bold active">Debt Details</a>
        <a href="#tab2" class="nav-item nav-link font-weight-bold">Approval History</a>
    </nav>
    <div class="tab-content px-2 card pt-3 pb-2">
        <div id="tab1" class="tab-pane fade active show">
            <div class="row my-2">
                <div>
                    <div class="card-body">
                        <div>
                            <h6 class="text-uppercase mt-2 ml-2">Debt Details</h6>
                            <hr>
                            @if ($debt->recovery_measure_status == 'approved')
                            <div class="card-tools">
                                <a href="{{ route('debts.debt.sendDemandNotice', encrypt($debt->id)) }}" class="btn btn-info btn-sm text-white"
                                    style="color: white !important;"><i class="fa fa-plus text-white"></i>
                                    Send Demand Notice Email</a>
                            </div>
                            @endif
                        </div>
 
                        <div class="row m-2 pt-3">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Status</span>
                                <p class="my-1">{{ $debt->app_step }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Tax Type</span>
                                <p class="my-1">{{ $debt->taxType->name }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Principal Amount</span>
                                <p class="my-1">{{ $debt->currency }}.
                                    {{ number_format($debt->principal_amount, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Penalty</span>
                                <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->penalty, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Interest</span>
                                <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->interest, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Total Amount</span>
                                <p class="my-1">{{ $debt->currency }}.
                                    {{ number_format($debt->original_total_amount, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
                                <p class="my-1">{{ $debt->currency }}.
                                    {{ number_format($debt->outstanding_amount, 2) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Payment Due Date</span>
                                <p class="my-1">{{ $debt->curr_due_date }}</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            @if (count($debt->recoveryMeasures))
                <div class="card p-0 m-0 mt-4">
                    <div class="card-body mt-0 p-2">
                        <h6 class="text-uppercase mt-2 ml-2">Recommended Recovery Measures</h6>
                        <hr>
                        <div class="row m-2 pt-3">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Status</span><br>
                                <span class="badge badge-primary">{{ $debt->recovery_measure_status }}</span>
                            </div>

                            @foreach ($debt->recoveryMeasures as $key => $recovery_measure)
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Measure Type</span>
                                    <p class="my-1">{{ $key + 1 }}. {{ $recovery_measure->category->name }}</p>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            @endif

            @if ($debt->recovery_measure_status != 'approved')
                @livewire('approval.recovery-measure-approval-processing', ['debtId' => $debt->id])
            @endif

        </div>
        <div id="tab2" class="tab-pane fade m-4">
            <livewire:approval.approval-history-table modelName='App\Models\Debts\Debt'
                modelId="{{ $debt->id }}" />
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
