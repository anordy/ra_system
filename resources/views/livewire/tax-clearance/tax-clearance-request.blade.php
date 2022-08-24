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
                    @if ($taxClearence->status === 'approved')
                        <span class="font-weight-bold text-success">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            Approved
                        </span>
                    @elseif($taxClearence->status === 'requested')
                        <span class="font-weight-bold text-warning">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            Requested
                        </span>
                    @elseif($taxClearence->status === 'rejected')
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
                <p class="my-1">{{ $taxClearence->businessLocation->business->name }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Branch Name</span>
                <p class="my-1">{{ $taxClearence->businessLocation->name }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Business Category</span>
                <p class="my-1">{{ $taxClearence->businessLocation->business->category->name }}</p>
            </div>
            @if ($taxClearence->businessLocation->business->alt_mobile)
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Alternative Mobile No.</span>
                    <p class="my-1">{{ $taxClearence->businessLocation->business->alt_mobile }}</p>
                </div>
            @endif
            @if ($taxClearence->businessLocation->business->email_address)
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Email Address</span>
                    <p class="my-1">{{ $taxClearence->businessLocation->business->email }}</p>
                </div>
            @endif
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Place of Business</span>
                <p class="my-1">
                    {{ $taxClearence->businessLocation->region->name }},
                    {{ $taxClearence->businessLocation->district->name }},
                    {{ $taxClearence->businessLocation->ward->name }}
                </p>
            </div>
            @if ($taxClearence->status === App\Enum\TaxClearanceStatus::APPROVED)
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Clearance Pdf</span>
                    <div class="my-1">
                        <a href="{{ route('tax-clearance.certificate', encrypt($taxClearence->id)) }}"
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
                    {{ $taxClearence->reason }}.
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
                                @if (count($returnDebts))
                                    @foreach ($returnDebts as $return)
                                        <tr>
                                            <td>{{ $return->taxType->name }}</td>
                                            {{-- Principal amount --}}
                                            <td>
                                                @if ($return->total_amount_due)
                                                    {{ number_format($return->total_amount_due, 2) }}
                                                    {{ $return->currency }}
                                                @endif

                                                @if ($return->total_vat_payable_tzs || $return->total_vat_payable_usd)
                                                    {{ number_format($return->total_vat_payable_tzs, 2) }} TZS <br>
                                                    {{ number_format($return->total_vat_payable_usd, 2) }} USD
                                                @endif
                                            </td>
                                            {{-- Penalty amount --}}
                                            <td>
                                                @if (get_class($return) == 'App\Models\Returns\EmTransactionReturn' ||
                                                    get_class($return) == 'App\Models\Returns\MmTransferReturn')
                                                    {{ number_format($return->total_amount_due_with_penalties - $return->total_amount_due, 2) }}
                                                    {{ $return->currency }}
                                                @else
                                                    @if ($return->penalty)
                                                        {{ number_format($return->penalty, 2) }}
                                                        {{ $return->currency }}
                                                    @endif
                                                @endif

                                                @if ($return->penalty_tzs || $return->penalty_usd)
                                                    {{ number_format($return->penalty_tzs, 2) }} TZS <br>
                                                    {{ number_format($return->penalty_usd, 2) }} USD
                                                @endif
                                            </td>
                                            {{-- Interest amount --}}
                                            <td>
                                                @if ($return->interest)
                                                    {{ number_format($return->interest, 2) }} {{ $return->currency }}
                                                @endif

                                                @if ($return->interest_tzs || $return->interest_usd)
                                                    {{ number_format($return->interest_tzs, 2) }} TZS <br>
                                                    {{ number_format($return->interest_usd, 2) }} USD
                                                @endif
                                            </td>
                                            {{-- Total debt --}}
                                            <td>
                                                @if ($return->total_amount_due_with_penalties)
                                                    {{ number_format($return->total_amount_due_with_penalties, 2) }}
                                                    {{ $return->currency }}
                                                @endif

                                                @if ($return->total_amount_due_with_penalties_tzs || $return->total_amount_due_with_penalties_usd)
                                                    {{ number_format($return->total_amount_due_with_penalties_tzs, 2) }}
                                                    TZS <br>
                                                    {{ number_format($return->total_amount_due_with_penalties_usd, 2) }}
                                                    USD
                                                @endif
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
                                    <th>Total Debt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($verificationDebts))
                                    @foreach ($verificationDebts as $verification)
                                        <tr>
                                            <td>{{ number_format($verification->principal_amount, 2) }}</td>
                                            <td>{{ number_format($verification->penalty_amount, 2) }}</td>
                                            <td>{{ number_format($verification->interest_amount, 2) }}</td>
                                            <td>{{ number_format($verification->principal_amount + $verification->penalty_amount + $verification->interest_amount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center py-3">
                                            No verification debts.
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
                                    <th>Total Debt</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($auditDebts))
                                    @foreach ($auditDebts as $audit)
                                        <tr>
                                            <td>{{ number_format($audit->principal_amount, 2) }}</td>
                                            <td>{{ number_format($audit->penalty_amount, 2) }}</td>
                                            <td>{{ number_format($audit->interest_amount, 2) }}</td>
                                            <td>{{ number_format($audit->principal_amount + $audit->penalty_amount + $audit->interest_amount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center py-3">
                                            No audit debts.
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

    <div class="tab-pane fade" id="investigation-debts" role="tabpanel" aria-labelledby="investigation-debts-tab">
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
                                    <th>Total Debt</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($investigationDebts))
                                    @foreach ($investigationDebts as $investigation)
                                        <tr>
                                            <td>{{ number_format($investigation->principal_amount, 2) }}</td>
                                            <td>{{ number_format($investigation->penalty_amount, 2) }}</td>
                                            <td>{{ number_format($investigation->interest_amount, 2) }}</td>
                                            <td>{{ number_format($investigation->principal_amount + $investigation->penalty_amount + $investigation->interest_amount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center py-3">
                                            No investigation debts.
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
