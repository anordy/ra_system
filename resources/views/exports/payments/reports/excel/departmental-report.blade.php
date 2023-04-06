<html>
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
                <strong>Currency</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Principal Amount</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Interest Amount</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Penalty Amount</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Total Amount</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Outstanding Amount</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Payment Date</strong>
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
                {{ $record->location->name ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->taxType->name ?? '-' }}
            </td>

            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->currency ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->principal===null?'-':number_format($record->principal, 2) }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{$record->interest===null?'-':number_format($record->interest, 2) }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{$record->penalty===null?'-':number_format($record->penalty, 2) }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{$record->total_amount===null?'-':number_format($record->total_amount, 2) }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{$record->outstanding_amount===null?'-':number_format($record->outstanding_amount, 2) }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ date('M, d Y', strtotime($record->created_at)) }}
            </td>

            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->paid_at==null ? '-' : date('M, d Y', strtotime($record->paid_at)) }}
            </td>

            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                @if(!is_null($record->paid_at))
                Paid
                @else
                Not Paid
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</html>