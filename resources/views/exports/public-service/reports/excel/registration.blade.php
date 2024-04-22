<html>
<table style="border-collapse:collapse;">
    <thead>
    <tr>
        <th style="text-align:center;" colspan="15">
            <strong>ZANZIBAR Revenue Authority</strong><br>
            <strong>Report of {{ $parameters['reg_type']  }} Public Service Registration </strong><br>
            @if($parameters['range_start'] && $parameters['range_end'])
                <strong>From {{ date("M, d Y", strtotime($parameters['range_start'])) }} To {{ date("M, d Y",
                    strtotime($parameters['range_end'])) }} </strong><br>
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
                <strong>Business</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Plate Number</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Payment Months</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Registration Type</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Vehicle Class</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Registered On</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Public Service Status</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>MVR Status</strong>
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach ($records as $i => $record)
            <tr>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;width: 10px">
                    {{ $i + 1 }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->plate_number ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->payment_months ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->registration_type ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->class_name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ date('M, d Y', strtotime($record->approved_on)) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->public_service_status ? strtoupper($record->public_service_status) : '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->mvr_status ?? '-' }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</html>