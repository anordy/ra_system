<div class="col-md-12">
    <table class="table table-bordered table-sm normal-text">
        <thead>
            <tr>
                <th>Month</th>
                <th>Tax Amount</th>
                <th>Late Filing Amount</th>
                <th>Late Payment Amount</th>
                <th>Interest Rate</th>
                <th>Interest Amount</th>
                <th>Penalty Amount</th>
            </tr>
        </thead>

        <tbody>
            @if (is_array($penalties_tzs) && count($penalties_tzs))
                @foreach ($penalties_tzs as $penalty)
                    <tr>
                        <td>{{ $penalty['returnMonth'] }}</td>
                        <td>{{ number_format($penalty['taxAmount'], 2) }}</td>
                        <td>{{ number_format($penalty['lateFilingAmount'], 2) }}</td>
                        <td>{{ number_format($penalty['latePaymentAmount'], 2) }}</td>
                        <td>{{ number_format($penalty['interestRate'], 2) }}</td>
                        <td>{{ number_format($penalty['interestAmount'], 2) }}</td>
                        <td>{{ number_format($penalty['penaltyAmount'], 2) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center py-3">
                        No penalties for this return.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    @if (is_array($penalties_usd) && count($penalties_usd) > 0)
        <table class="table table-bordered table-sm normal-text">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Tax Amount($)</th>
                    <th>Late Filing Amount($)</th>
                    <th>Late Payment Amount($)</th>
                    <th>Interest Rate</th>
                    <th>Interest Amount</th>
                    <th>Penalty Amount($)</th>
                </tr>
            </thead>

            <tbody>
                @if (is_array($penalties_usd) && count($penalties_usd))
                    @foreach ($penalties_usd as $penalty)
                        <tr>
                            <td>{{ $penalty['returnMonth'] }}</td>
                            <td>{{ number_format($penalty['taxAmount'], 2) }}</td>
                            <td>{{ number_format($penalty['lateFilingAmount'], 2) }}</td>
                            <td>{{ number_format($penalty['latePaymentAmount'], 2) }}</td>
                            <td>{{ number_format($penalty['interestRate'], 2) }}</td>
                            <td>{{ number_format($penalty['interestAmount'], 2) }}</td>
                            <td>{{ number_format($penalty['penaltyAmount'], 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center py-3">
                            No usd penalties for this return.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endif

    @include($actionsView);
</div>
