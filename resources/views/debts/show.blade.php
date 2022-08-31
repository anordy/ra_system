@extends('layouts.master')

@section('title', 'Show Debt')

@section('content')
    <div class="card-body pb-0">
        <nav class="nav nav-tabs mt-0 border-top-0">
            <a href="#tab1" class="nav-item nav-link font-weight-bold active">Debt Details</a>
            @if ($debt->debtWaiver)
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Waiver Details</a>
            @endif
            <a href="#tab3" class="nav-item nav-link font-weight-bold">Debt Penalties</a>

        </nav>

        <div class="tab-content px-2 card pt-3 pb-2">
            <div id="tab1" class="tab-pane fade active show m-4">
                <h6 class="text-uppercase mt-2 ml-2">Debt Details</h6>
                <hr>
                <div class="row m-2 pt-3">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Name</span>
                        <p class="my-1">{{ $debt->business->name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Location</span>
                        <p class="my-1">{{ $debt->location->name ?? 'Head Quarter' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">ZIN No.</span>
                        <p class="my-1">{{ $debt->location->zin }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Debt Status</span>
                        <p class="my-1"><span class="badge badge-info">{{ $debt->app_step }}</span></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Tax Type</span>
                        <p class="my-1">{{ $debt->taxType->name }}</p>
                    </div>

                </div>

                <div>

                    <h6 class="text-uppercase mt-2 ml-2">Debt Payment Figures</h6>
                    <hr>
                    <div class="row m-2 pt-3">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Principal Amount</span>
                            <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->principal_amount, 2) }}</p>
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
                            <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->total_amount, 2) }}</p>
                        </div>
                        @if ($debt->status != 'submitted')
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Payment Status</span>
                                <p class="my-1"><span class="badge badge-info">{{ $debt->status }}</span></p>
                            </div>
                        @endif

                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Payment Due Date</span>
                            <p class="my-1">{{ $debt->curr_due_date }}</p>
                        </div>
                    </div>
                </div>

                @if ($debt->demand_notice_count > 0)
                    <div class="card-body">
                        <div>
                            <h6 class="text-uppercase mt-2 ml-2">Demand Notice Details</h6>
                            <hr>
                        </div>

                        <div class="row m-2 pt-3">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Sent Demand Notice Count</span>
                                <p class="my-1">{{ $debt->demand_notice_count }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Next Demand Notice Date</span>
                                <p class="my-1">{{ $debt->next_demand_notice_date }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div id="tab2" class="tab-pane fade  m-4">
                @if ($debt->debtWaiver)
                    <h6 class="text-uppercase mt-2 ml-2">Original Debt Details</h6>
                    <hr>
                    <div class="row m-2 pt-3">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Principal Amount</span>
                            <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->principal_amount, 2) }}
                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Penalty</span>
                            <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->original_penalty, 2) }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Interest</span>
                            <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->original_interest, 2) }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Total Amount</span>
                            <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->original_total_amount, 2) }}
                            </p>
                        </div>
                    </div>

                    <h6 class="text-uppercase mt-2 ml-2">Waiver Details</h6>
                    <hr>
                    <div class="row m-2 pt-3">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Waiver Type</span>
                            <p class="my-1">{{ $debt->debtWaiver->category }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Waiver Status</span>
                            <p class="my-1"><span class="badge badge-info">{{ $debt->debtWaiver->status }}</span></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Principal Amount</span>
                            <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->principal_amount, 2) }}
                            </p>
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
                            <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->total_amount, 2) }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
                            <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->outstanding_amount, 2) }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
            <div id="tab3" class="tab-pane fade m-4">
                @if (count($debt->penalties) > 0)
                    <div>
                        <h6 class="text-uppercase mt-2 ml-2">Debt Penalties</h6>
                        <hr>
                        <livewire:debt.debt-penalties :penalties="$debt->penalties" />
                    </div>
                @endif
            </div>

            <div id="tab3" class="tab-pane fade m-4">
                <livewire:debt.demand-notice.demand-notice-table debtId="{{ $debt->id }}" />
            </div>
        </div>

    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            $(".nav-tabs a").click(function() {
                $(this).tab('show');
            });
        });
    </script>
@endsection
