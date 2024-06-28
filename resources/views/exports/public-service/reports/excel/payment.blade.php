<html>
<table style="border-collapse:collapse;">
    <thead>
    <tr>
        <th style="text-align:center;" colspan="15">
            <strong>ZANZIBAR Revenue Authority</strong><br>
            <strong>Report of {{ $parameters['payment_type']  }} Transport Service Payments </strong><br>
            @if($parameters['range_start'] && $parameters['range_end'])
                <strong>From {{ date("M, d Y", strtotime($parameters['range_start'])) }} To {{ date("M, d Y",
                    strtotime($parameters['range_end'])) }} </strong><br>
            @endif

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
            <strong>Business</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Plate Number</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Registration Type</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Currency</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Amount</strong>
        </th>
        <th style="text-align:center; border-collapse:collapse;border: 1px solid black;">
            <strong>Start Date</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>End Date</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Payment Status</strong>
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
                {{ $record->business->name ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->motor->mvr->plate_number ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->motor->mvr->regtype->name ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->currency ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->amount ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ date('M, d Y', strtotime($record->start_date)) }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->end_date==null?'-':date('M, d Y', strtotime($record->end_date)) }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                @if (!$record->paid_at)
                    Not Paid
                @else
                    Paid
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

</html>