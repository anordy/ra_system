<ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
            aria-selected="true">Tax Clearence Information</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="return-debts-tab" data-toggle="tab" href="#return-debts" role="tab"
            aria-controls="return-debts" aria-selected="false">Return Debts</a>
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
        <a class="nav-link" id="investigation-debts-tab" data-toggle="tab" href="#investigation-debts" role="tab"
            aria-controls="investigation-debts" aria-selected="false">Investigation Debts</a>
    </li>

</ul>

<div class="tab-content bg-white border shadow-sm" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="row m-2 pt-3">
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Tax Clearence Status</span>
                <p class="my-1">
                    @if ($taxClearance->status === 'approved')
                        <span class="font-weight-bold text-success">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            Approved
                        </span>
                    @elseif($taxClearance->status === 'requested')
                        <span class="font-weight-bold text-warning">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            Requested
                        </span>
                    @elseif($taxClearance->status === 'rejected')
                        <span class="font-weight-bold text-danger">
                            <i class="bi bi-pen-fill mr-1"></i>
                            Rejected
                        </span>
                    @else
                        <span class="font-weight-bold text-info">
                            <i class="bi bi-clock-history mr-1"></i>
                            Waiting Approval
                        </span>
                    @endif
                </p>
            </div>

            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Business Name</span>
                <p class="my-1">{{ $taxClearance->businessLocation->business->name }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Branch Name</span>
                <p class="my-1">{{ $taxClearance->businessLocation->name }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Business Category</span>
                <p class="my-1">{{ $taxClearance->businessLocation->business->category->name }}</p>
            </div>
            @if ($taxClearance->businessLocation->business->alt_mobile)
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Alternative Mobile No.</span>
                    <p class="my-1">{{ $taxClearance->businessLocation->business->alt_mobile }}</p>
                </div>
            @endif
            @if ($taxClearance->businessLocation->business->email_address)
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Email Address</span>
                    <p class="my-1">{{ $taxClearance->businessLocation->business->email }}</p>
                </div>
            @endif
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Place of Business</span>
                <p class="my-1">
                    {{ $taxClearance->businessLocation->region->name }},
                    {{ $taxClearance->businessLocation->district->name }},
                    {{ $taxClearance->businessLocation->ward->name }}
                </p>
            </div>
            @if ($taxClearance->status === App\Enum\TaxClearanceStatus::APPROVED)
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Clearance Pdf</span>
                    <div class="my-1">
                        <a target="_blank" href="{{ route('tax-clearance.certificate', encrypt($taxClearance->id)) }}"
                            class="btn btn-info btn-sm">
                            <i class="bi bi-download mr-1"></i>
                            Download
                        </a>
                    </div>
                </div>
            @endif
            <div class="col-md-8 mb-3">
                <span class="font-weight-bold text-uppercase">Reason</span>
                <p class="my-1">
                    {{ $taxClearance->reason }}.
                </p>
            </div>
        </div>

    </div>

    <div class="tab-pane fade" id="return-debts" role="tabpanel" aria-labelledby="return-debts-tab">
        <div class="card shadow-sm my-4 rounded-0">
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
                                @if (count($debts))
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

</div>
