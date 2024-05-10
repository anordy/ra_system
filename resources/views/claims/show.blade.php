@extends("layouts.master")

@section("title", "Tax Claims")

@section("content")
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Claim Details
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-12 mt-3">
                    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="claim-details-tab" data-toggle="tab" href="#claim-details"
                                role="tab" aria-controls="claim-details" aria-selected="true">Claim Details</a>
                        </li>
                        @if ($claim->oldReturn)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="old-return-tab" data-toggle="tab" href="#old-return" role="tab"
                                    aria-controls="old-return" aria-selected="true">Filed Return</a>
                            </li>
                        @endif
                        @if ($claim->newReturn)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="new-return-tab" data-toggle="tab" href="#new-return" role="tab"
                                    aria-controls="new-return" aria-selected="false">New Return</a>
                            </li>
                        @endif
                        @if (!empty($claim->credit))
                            @if (count($claim->credit->items))
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="credit-items-tab" data-toggle="tab" href="#credit-items"
                                        role="tab" aria-controls="credit-items" aria-selected="false">Credit Items</a>
                                </li>
                            @endif
                        @endif
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="approval-history-tab" data-toggle="tab" href="#approval-history"
                                role="tab" aria-controls="approval-history" aria-selected="true">Approval History</a>
                        </li>
                    </ul>
                    <div class="tab-content bg-white border shadow-sm" id="myTabContent" style="padding: 1rem !important;">
                        <div class="tab-pane fade  show active" id="claim-details" role="tabpanel"
                            aria-labelledby="claim-details-tab">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Business Name</span>
                                    <p class="my-1">{{ $claim->business->name }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Business Location</span>
                                    <p class="my-1">{{ $claim->location->name ?? "Headquarter" }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Financial Month</span>
                                    <p class="my-1">{{ $claim->financialMonth->name }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Financial Year</span>
                                    <p class="my-1">{{ $claim->financialMonth->year->name }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Initial Claimed Amount</span>
                                    <p class="my-1">{{ number_format($claim->original_figure, 2) }} {{ $claim->currency }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Agreed Claim Amount</span>
                                    <p class="my-1" id="agreed-amount">{{ number_format($claim->amount, 2) }} {{ $claim->currency }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Claim Status</span>
                                    <p class="my-1">{{ ucfirst($claim->status) }}</p>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <livewire:claims.set-figure.set-figure-component :taxClaimId="$claim->id" />
                                </div>
                            </div>
                        </div>
                        @if ($return = $claim->oldReturn)
                            <div class="tab-pane fade" id="old-return" role="tabpanel" aria-labelledby="old-return-tab">
                                @include($returnView)
                            </div>
                        @endif
                        @if ($return = $claim->newReturn)
                            <div class="tab-pane fade" id="new-return" role="tabpanel" aria-labelledby="new-return-tab">
                                @include($returnView)
                            </div>
                        @endif
                        @if (!empty($claim->credit))
                            @if (count($claim->credit->items))
                                <div class="tab-pane fade" id="credit-items" role="tabpanel"
                                    aria-labelledby="credit-items-tab">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Amount</th>
                                                <th>Return For</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($claim->credit->items as $item)
                                                <tr>
                                                    <th>{{ $item->amount }} {{ $item->currency }}</th>
                                                    <th>{{ $item->returnable->financialMonth->name }}
                                                        {{ $item->returnable->financialMonth->year->name }}</th>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif
                        <div class="tab-pane fade" id="approval-history" role="tabpanel" aria-labelledby="old-return-tab">
                            <livewire:approval.approval-history-table modelName='App\Models\Claims\TaxClaim'
                                modelId="{{ encrypt($claim->id) }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (count($claim->officers))
        <div class="card rounded-0">
            <div class="card-header bg-white font-weight-bold">
                TAX CLAIM OFFICERS
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($claim->officers as $officer)
                        <div class="col-md-6 mb-3">
                            <span class="font-weight-bold text-uppercase">Officer Name</span>
                            <p class="my-1">{{ $officer->user->fullName }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if ($claim->assessment)
        <div class="card">
            <div class="card-header text-uppercase font-weight-bold bg-white">
                Assessment Details
            </div>
            <div class="card-body">
                <div class="row">
                    @if ($claim->assessment->report_path)
                        <div class="col-md-4">
                            <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                                class="p-2 mb-3 d-flex rounded-sm align-items-center">
                                <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                <a target="_blank"
                                    href="{{ route("claims.files.show", encrypt($claim->assessment->report_path)) }}"
                                    style="font-weight: 500;" class="ml-1">
                                    Assessment Report
                                    <i class="bi bi-arrow-up-right-square ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($credit = $claim->credit)
        <div class="card">
            <div class="card-header text-uppercase font-weight-bold bg-white">
                Payment Structure
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Payment Method</span>
                        <p class="my-1">{{ ucfirst($credit->payment_method) }}</p>
                    </div>
                    @if ($credit->installments_count)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Payment Method</span>
                            <p class="my-1">{{ $credit->installments_count }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Payment per Phase</span>
                            <p class="my-1">{{ number_format($credit->amount / $credit->installments_count, 2) }}
                                {{ $credit->currency }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <livewire:approval.tax-claim-approval-processing modelId="{{ encrypt($claim->id) }}"
        modelName="{{ get_class($claim) }}" />

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('agreedAmountUpdated', function(updatedAmount) {
                // Update the Agreed Claimed Amount in the parent blade file using jQuery with thousand separator
                $('#agreed-amount').text(new Intl.NumberFormat().format(updatedAmount) + ' {{ $claim->currency }}');
            });
        });
    </script>
@endsection
