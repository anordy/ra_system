<div class="row mx-1">
    <div class="col-md-12">
        <livewire:returns.return-payment :return="$tax_return" />
    </div>
</div> 
<table class="table table-bordered table-striped table-sm">
    <div class="d-flex justify-content-between align-items-center">
        <label class="text-left font-weight-bold text-uppercase">{{ $tax_return->business->name }} - {{ $tax_return->location->name }} {{ $tax_return->taxtype->name }} Debt for {{$tax_return->return->financialMonth->name}} {{ $tax_return->return->financialYear->code }}</label>
    </div>
    <thead>
        <th style="width: 20%"></th>
        <th class="text-uppercase" style="width: 30%">Return Figure</th>
        <th class="text-uppercase" style="width: 30%">Return Debt Figure</th>
    </thead>
    <tbody>
        <tr>
            <th>Principal Amount</th>
            <td>{{ $tax_return->return->currency }}. {{ number_format($tax_return->return->total_amount_due, 2) }}</td>
            <td>{{ $tax_return->currency }}. {{ number_format($tax_return->principal ?? 0, 2) }}</td>
        </tr>
        <tr>
            <th>Penalty Amount</th>
            <td>{{ $tax_return->return->currency }}. {{ number_format($tax_return->return->penalty, 2) }}</td>
            <td>{{ $tax_return->return->currency }}. {{ number_format($tax_return->return->penalty, 2) }}</td>
        </tr>
        <tr>
            <th>Interest Amount</th>
            <td>{{ $tax_return->return->currency }}. {{ number_format($tax_return->return->interest, 2) }}</td>
            <td>{{ $tax_return->return->currency }}. {{ number_format($tax_return->return->interest, 2) }}</td>
        </tr>
        <tr>
            <th>Total Payable Amount</th>
            <td>{{ $tax_return->return->currency }}. {{ number_format($tax_return->return->total_amount_due_with_penalties, 2) }}</td>
            <td>{{ $tax_return->return->currency }}. {{ number_format($tax_return->return->total_amount_due_with_penalties, 2) }}</td>
        </tr>
        <tr>
            <th>Outstanding Amount</th>
            <td>N/A</td>
            <td>{{ $tax_return->currency }}. {{ number_format($tax_return->outstanding_amount ?? 0, 2) }}</td>
        </tr>
        <tr>
            <th>Payment Due Date</th>
            <td>{{ formatDate($tax_return->return->payment_due_date) ?? '' }}</td>
            <td>{{ formatDate($tax_return->curr_payment_due_date) ?? '' }}</td>
        </tr>
        <tr>
            <th>Payment Status</th>
            <td></td>
            <td> 
                @if ($tax_return->status != 'submitted')
                <div class="mb-3">
                    <p class="my-1">
                        @if ($tax_return->payment_status == 'complete')
                            <span class="badge badge-success"
                                style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 100%; padding:3%">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                PAID
                            </span>
                        @elseif ($tax_return->payment_status == 'control-number-generated')
                            <span class="badge badge-warning "
                                style="border-radius: 1rem; background: #d4dc3559; color: #474704; font-size: 100%; padding:3%">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Control Number Generated
                            </span>
                        @elseif ($tax_return->payment_status == 'control-number-generating')
                            <span class="badge badge-warning "
                                style="border-radius: 1rem; background: #dcd43559; color: #474704; font-size: 100%; padding:3%">
                                <i class="fas fa-clock mr-1 "></i>
                                Control Number Generating
                            </span>
                        @elseif ($tax_return->payment_status == 'control-number-generating-failed')
                            <span class="badge badge-warning "
                                style="border-radius: 1rem; background: #f40f0b59; color: #5e3e3e; font-size: 80%; padding:3%">
                                <i class="fas fa-exclamation"> </i>
                                Control Number Generation Failed
                            </span>
                        @else
                            <span class="badge badge-warning "
                                style="border-radius: 1rem; background: #d1dc3559; color: #474704; font-size: 100%; padding:3%">
                                {{ $tax_return->payment_status }}
                            </span>
                        @endif
                    </p>
                </div>
            @endif</td>
        </tr>
        <tr>
            <th>Payment Method</th>
            <td></td>
            <td>{{ $tax_return->payment_method ?? 'N/A' }}</td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered table-striped table-sm">
    <div class="d-flex justify-content-between align-items-center">
        <label class="text-left font-weight-bold text-uppercase">Accumulated Penalties</label>
    </div>
    <thead>
        <tr>
            <th>Month</th>
            <th>Interval</th>
            <th>Tax Amount</th>
            <th>Late Filing Amount</th>
            <th>Late Payment Amount</th>
            <th>Interest Rate</th>
            <th>Interest Amount</th>
            <th>Penalty Amount</th>
        </tr>
    </thead>

    <tbody>
        @if (count($tax_return->return->penalties) > 0)
            @foreach ($tax_return->return->penalties as $penalty)
                <tr>
                    <td>{{ $penalty['financial_month_name'] ?? $penalty['return_quater'] }}</td>
                    <td>{{ formatDate($penalty['start_date']) }} to {{ formatDate($penalty['end_date']) }}</td>
                    <td>{{ number_format($penalty['tax_amount'], 2) }}</td>
                    <td>{{ number_format($penalty['late_filing'], 2) }}</td>
                    <td>{{ number_format($penalty['late_payment'], 2) }}</td>
                    <td>{{ number_format($penalty['rate_percentage'], 2) }}</td>
                    <td>{{ number_format($penalty['rate_amount'], 2) }}</td>
                    <td>{{ number_format($penalty['penalty_amount'], 2) }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="text-center py-3">
                    No penalties for this debt.
                </td>
            </tr>
        @endif
    </tbody>
</table>