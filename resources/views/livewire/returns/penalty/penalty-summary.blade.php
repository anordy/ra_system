<div class="col-md-12 px-0">
    <table class="table table-bordered normal-text">
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
                    <td>{{ $penalty['returnMonth'] }}</td>
                    <td>{{ number_format($penalty['taxAmount'], 2) }}</td>
                    <td>{{ number_format($penalty['lateFilingAmount'], 2) }}</td>
                    <td>{{ number_format($penalty['latePaymentAmount'], 2) }}</td>
                    <td>{{ number_format($penalty['interestRate'], 2) }}</td>
                    <td>{{ number_format($penalty['interestAmount'], 2) }}</td>
                    <td>{{ number_format($penalty['penaltyAmount'], 2)}}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="text-center py-3">
                    No penalties for this return.
                </td>
            </tr>
        @endif
        </tbody>
    </table>

    @if (isset($actionsView))
        @include($actionsView)
    @endif

</div>