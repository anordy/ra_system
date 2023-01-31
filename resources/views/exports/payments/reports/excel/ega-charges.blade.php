<html>
<table style="border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:center;" colspan="15">
                <strong>ZANZIBAR REVENUE BOARD</strong><br>
                <strong>Total Number of Records: {{ $records->count() }} </strong>
            </th>
        </tr>
    </thead>
</table>
<table>
    <thead>
        <tr>
            <th style="text-align:center; border-collapse:collapse;border: 1px solid black;">
                <strong>S/N</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Control number</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Currency</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Amount</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Payment Status</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Description</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Payer name</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Payer Email</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Payer Phone number</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($records as $index => $record)
            <tr>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $index + 1 }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->bill->control_number ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->currency ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ number_format($record->amount,2) ?? '-' }}
                </td>
    
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->bill->status ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->bill->description ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->bill->payer_name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->bill->payer_email ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->bill->payer_phone_number ?? '-' }}
                </td>
            </tr>
        @endforeach
        </tbody>

</table>

</html>