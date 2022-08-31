<html>
<table style="border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:center;" colspan="5" height="70">
                <strong>ZANZIBAR REVENUE BOARD</strong><br>
                <strong>{{ $title }}</strong><br>
                <strong>{{ $taxType->name }}</strong><br>
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
                <strong>Location</strong>
            </th>

            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Tax Type</strong>
            </th>

            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Taxpayer</strong>
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
                {{ $record->name }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $taxType->name }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->taxpayer->full_name ?? '-' }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</html>