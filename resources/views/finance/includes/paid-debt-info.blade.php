<div class="">
    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="unpaid-return-debts-{{$location_id}}-tab" data-toggle="tab" href="#unpaid-return-debts-{{$location_id}}" role="tab" aria-controls="unpaid-return-debts-{{$location_id}}"
                aria-selected="true">Return Debts</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="unpaid-verification-debts-{{$location_id}}-tab" data-toggle="tab" href="#unpaid-verification-debts-{{$location_id}}" role="tab"
                aria-controls="unpaid-verification-debts-{{$location_id}}" aria-selected="false">Verification Debts</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="unpaid-audit-debts-{{$location_id}}-tab" data-toggle="tab" href="#unpaid-audit-debts-{{$location_id}}" role="tab"
                aria-controls="unpaid-audit-debts-{{$location_id}}" aria-selected="false">Audit Debts</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="unpaid-investigation-debts-{{$location_id}}-tab" data-toggle="tab" href="#unpaid-investigation-debts-{{$location_id}}"
                role="tab" aria-controls="unpaid-investigation-debts-{{$location_id}}" aria-selected="false">Investigation Debts</a>
        </li>
        
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="unpaid-landlease-debts-{{$location_id}}-tab" data-toggle="tab" href="#unpaid-landlease-debts-{{$location_id}}"
                role="tab" aria-controls="unpaid-landlease-debts-{{$location_id}}" aria-selected="false">
                Land Lease Debts
            </a>
        </li>
    </ul>

    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade show active" id="unpaid-return-debts-{{$location_id}}" role="tabpanel" aria-labelledby="unpaid-return-debts-{{$location_id}}-tab">
            <div class="card shadow-sm my-2 rounded-0">
                <div class="card-header font-weight-bold bg-white">
                    Return Debts
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="card-body mt-0 p-2">
                            <table class="table table-md">
                                <thead>
                                    <tr>
                                        <th>Tax Type</th>
                                        <th>Principal Amount</th>
                                        <th>Penalty Amount</th>
                                        <th>Interest Amount</th>
                                        <th>Total Debt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($tax_return_debts))
                                        @foreach ($tax_return_debts as $debt)
                                            <tr>
                                                <td>{{ $debt->taxtype->name }}</td>
                                                <td>
                                                    {{ number_format($debt->principal, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    {{ number_format($debt->penalty, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    {{ number_format($debt->interest, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    {{ number_format($debt->outstanding_amount, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center py-3">
                                                No debts for returns.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="unpaid-verification-debts-{{$location_id}}" role="tabpanel" aria-labelledby="unpaid-verification-debts-{{$location_id}}-tab">
            <div class="card shadow-sm my-4 rounded-0">
                <div class="card-header font-weight-bold bg-white">
                    Verification Debts
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="card-body mt-0 p-2">
                            <table class="table table-md">
                                <thead>
                                    <tr>
                                        <th>Principal</th>
                                        <th>Penalty</th>
                                        <th>Interest</th>
                                        <th>Installment</th>
                                        <th>Total Debt</th>
                                        <th>Assessment Step</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($verificateionDebts))
                                        @foreach ($verificateionDebts as $debt)
                                            <tr>
                                                <td>
                                                    {{ number_format($debt->original_principal_amount, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    {{ number_format($debt->penalty_amount, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    {{ number_format($debt->interest_amount, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    {{ $debt->installment->installment_count ?? '0'  }}
                                                </td>
                                                <td>
                                                    {{ number_format($debt->outstanding_amount, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    @include('finance.includes.assessment-status')
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center py-3">
                                                No debts.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="unpaid-audit-debts-{{$location_id}}" role="tabpanel" aria-labelledby="unpaid-audit-debts-{{$location_id}}-tab">
            <div class="card shadow-sm my-4 rounded-0">
                <div class="card-header font-weight-bold bg-white">
                    Audit Debts
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="card-body mt-0 p-2">
                            <table class="table table-md">
                                <thead>
                                    <tr>
                                        <th>Principal</th>
                                        <th>Penalty</th>
                                        <th>Interest</th>
                                        <th>Installment</th>
                                        <th>Total Debt</th>
                                        <th>Assessment Step</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($auditDebts))
                                        @foreach ($auditDebts as $debt)
                                            <tr>
                                                <td>
                                                    {{ number_format($debt->original_principal_amount, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    {{ number_format($debt->penalty_amount, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    {{ number_format($debt->interest_amount, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    {{ $debt->installment->installment_count ?? '0'  }}
                                                </td>
                                                <td>
                                                    {{ number_format($debt->outstanding_amount, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    @include('finance.includes.assessment-status')
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center py-3">
                                                No debts.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="unpaid-investigation-debts-{{$location_id}}" role="tabpanel"
            aria-labelledby="unpaid-investigation-debts-{{$location_id}}-tab">
            <div class="card shadow-sm my-4 rounded-0">
                <div class="card-header font-weight-bold bg-white">
                    Investigation Debts
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="card-body mt-0 p-2">
                            <table class="table table-md">
                                <thead>
                                    <tr>
                                        <th>Principal</th>
                                        <th>Penalty</th>
                                        <th>Interest</th>
                                        <th>Installment</th>
                                        <th>Total Debt</th>
                                        <th>Assessment Step</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($investigationDebts))
                                        @foreach ($investigationDebts as $debt)
                                            <tr>
                                                <td>
                                                    {{ number_format($debt->original_principal_amount, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    {{ number_format($debt->penalty_amount, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    {{ number_format($debt->interest_amount, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    {{ $debt->installment->installment_count ?? '0'  }}
                                                </td>
                                                <td>
                                                    {{ number_format($debt->outstanding_amount, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    @include('finance.includes.assessment-status')
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center py-3">
                                                No debts.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="unpaid-landlease-debts-{{$location_id}}" role="tabpanel" aria-labelledby="unpaid-landlease-debts-{{$location_id}}-tab">
            <div class="card shadow-sm my-4 rounded-0">
                <div class="card-header font-weight-bold bg-white">
                    Land Lease Debts
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="card-body mt-0 p-2">
                            <table class="table table-md">
                                <thead>
                                    <tr>
                                        <th>Tax Type</th>
                                        <th>Year</th>
                                        <th>Principal Amount</th>
                                        <th>Penalty Amount</th>
                                        <th>Total Debt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($land_lease_debts))
                                        @foreach ($land_lease_debts as $debt)
                                            <tr>
                                                <td>{{ $debt->businessLocation->name }}</td>
                                                <td>
                                                    {{$debt->LeasePayment->financialYear->code}}
                                                </td>
                                                <td>
                                                    {{ number_format($debt->original_total_amount, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    {{ number_format($debt->penalty, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                                <td>
                                                    {{ number_format($debt->outstanding_amount, 2) }}
                                                    {{ $debt->currency }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center py-3">
                                                No debts for returns.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

