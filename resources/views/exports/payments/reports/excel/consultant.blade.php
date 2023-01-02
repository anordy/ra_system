<html>
<table style="border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:center;" colspan="15">
                <strong>ZANZIBAR REVENUE BOARD</strong><br>
                @if ($parameters['dates']['startDate'] != null)
                <strong>From {{ date("M, d Y", strtotime($parameters['dates']['from'])) }} To {{ date("M, d Y",
                    strtotime($parameters['dates']['to'])) }} </strong><br>
                @endif
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
            <strong>Full Name</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Phone Number</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Email</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Amount</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Currency</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Description</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Control Number</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Status</strong>
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
                {{ $record->payer_name ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->payer_phone_number ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->payer_email ?? '-' }}
            </td>

            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->amount ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->currency ?? '-' }}
            </td>

            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->description ?? '-' }}
            </td>

            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->control_number ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->status ?? '-' }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

</html>