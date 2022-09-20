<div class="">
    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="return-debts-tab" data-toggle="tab" href="#return-debts" role="tab" aria-controls="return-debts"
                aria-selected="true">Return Debts</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="verification-debts-tab" data-toggle="tab" href="#verification-debts" role="tab"
                aria-controls="verification-debts" aria-selected="false">Verification Debts</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="audit-debts-tab" data-toggle="tab" href="#audit-debts" role="tab"
                aria-controls="audit-debts" aria-selected="false">Audit Debts</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="investigation-debts-tab" data-toggle="tab" href="#investigation-debts"
                role="tab" aria-controls="investigation-debts" aria-selected="false">Investigation Debts</a>
        </li>
        
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="landlease-debts-tab" data-toggle="tab" href="#landlease-debts"
                role="tab" aria-controls="landlease-debts" aria-selected="false">
                Land Lease Debts
            </a>
        </li>
    </ul>

    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade show active" id="return-debts" role="tabpanel" aria-labelledby="return-debts-tab">
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
        <div class="tab-pane fade" id="verification-debts" role="tabpanel" aria-labelledby="verification-debts-tab">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($debts))
                                        @foreach ($debts as $debt)
                                            @if ($debt->taxtype->code == \App\Models\TaxType::VERIFICATION)
                                                <tr>
                                                    <td>
                                                        {{ number_format($debt->original_principal_amount, 2) }}
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
                                                        {{ $debt->installment->installment_count ?? '0'  }}
                                                    </td>
                                                    <td>
                                                        {{ number_format($debt->outstanding_amount, 2) }}
                                                        {{ $debt->currency }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center py-3">
                                                No debts for verification.
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

        <div class="tab-pane fade" id="audit-debts" role="tabpanel" aria-labelledby="audit-debts-tab">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($debts))
                                        @foreach ($debts as $debt)
                                            @if ($debt->taxtype->code == \App\Models\TaxType::AUDIT)
                                                <tr>
                                                    <td>
                                                        {{ number_format($debt->original_principal_amount, 2) }}
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
                                                        {{ $debt->installment->installment_count ?? '0'  }}
                                                    </td>
                                                    <td>
                                                        {{ number_format($debt->outstanding_amount, 2) }}
                                                        {{ $debt->currency }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center py-3">
                                                No debts for audit.
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

        <div class="tab-pane fade" id="investigation-debts" role="tabpanel"
            aria-labelledby="investigation-debts-tab">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($debts))
                                        @foreach ($debts as $debt)
                                            @if ($debt->taxtype->code == \App\Models\TaxType::INVESTIGATION)
                                                <tr>
                                                    <td>
                                                        {{ number_format($debt->original_principal_amount, 2) }}
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
                                                        {{ $debt->installment->installment_count ?? '0'  }}
                                                    </td>
                                                    <td>
                                                        {{ number_format($debt->outstanding_amount, 2) }}
                                                        {{ $debt->currency }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center py-3">
                                                No debts for investigation.
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

        <div class="tab-pane fade" id="landlease-debts" role="tabpanel" aria-labelledby="landlease-debts-tab">
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

    </div>

</div>
