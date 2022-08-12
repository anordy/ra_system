<div>
    <table class="table table-bordered table-stripped">
        <thead>
            <tr>
                <th>Total Submitted</th>
                <th>Total Paid</th>
                <th>Total unpaid</th>
                <th>Late Filings</th>
                <th>Late Paid</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $totalSubmittedReturns }}</td>
                <td>{{ $totalPaidReturns }}</td>
                <td>{{ $totalUnpaidReturns }}</td>
                <td>{{ $totalLateFiledReturns }}</td>
                <td>{{ $totalLatePaidReturns }}</td>
            </tr>
        </tbody>
    </table>
</div>
