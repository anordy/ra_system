<html>
<table style="border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:center;" colspan="8" height="70">
                <strong>ZANZIBAR REVENUE BOARD</strong><br>
                {{-- <strong>{{ $title }}</strong><br>
                <strong>{{ $taxType->name }}</strong><br> --}}
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

            {{-- <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Tax Type</strong>
            </th> --}}
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Business Category</strong>
            </th>

            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Taxpayer</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Date of Commensing</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Region</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Physical Address</strong>
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
            {{-- <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->business-> }}
            </td> --}}
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->business->category->name ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->taxpayer->fullname ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ date('M, d Y', strtotime($record->date_of_commencing)) ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->region->name ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->physical_address ?? '-' }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</html>