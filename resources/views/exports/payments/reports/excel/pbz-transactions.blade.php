<html>
<table style="border-collapse:collapse;">
    <thead>
    <tr>
        <th style="text-align:center;" colspan="7" height="70px">
            <strong>CRDB BANK PLC Authority</strong><br>
            <strong>{{ $title }}</strong><br>
            <strong>Total Number of Records: {{ $records->count() }} </strong>
        </th>
    </tr>
    </thead>
</table>
<table class="table">
    <thead class="tableHead">
    <tr>
        <th style="text-align:center; border-collapse:collapse;border: 1px solid black;">
            <strong>S/N</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Control number</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Paid Amount</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Currency</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Bank Ref</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Transaction Time</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Has ZanMalipo Bill</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>ZanMalipo Status</strong>
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach ($records as $index => $record)
        <tr>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $index + 1 }}
            </td>
            <td  style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                &nbsp;{{ $record->control_number ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ number_format($record->amount, 2) ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->currency ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->bank_ref ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->transaction_time ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->bill ? 'YES ' : 'NO' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black; text-transform: uppercase">
                {{ $record->bill ? strtoupper($record->bill->status) : 'N/A' }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</html>