<table class="table table-bordered table-sm mb-0">
    <thead>
    <tr>
        <th>Month</th>
        <th>Tax Amount</th>
        <th>Late Filing Amount</th>
        <th>Late Payment Amount</th>
        <th>Interest Rate</th>
        <th>Interest Amount</th>
        <th>Payable Amount</th>
    </tr>
    </thead>

    <tbody>
    @if(is_array($penalties) && count($penalties))
        @foreach ($penalties as $penalty)
            <tr>
                <td>{{ $penalty['financial_month_name'] ?? $penalty['return_quater'] }}</td>
                <td>{{ number_format($penalty['tax_amount'], 2) }}</td>
                <td>{{ number_format($penalty['late_filing'], 2) }}</td>
                <td>{{ number_format($penalty['late_payment'], 2) }}</td>
                <td>{{ number_format($penalty['rate_percentage'], 4) }}</td>
                <td>{{ number_format($penalty['rate_amount'], 2) }}</td>
                <td>{{ number_format($penalty['penalty_amount'], 2)}}</td>
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
