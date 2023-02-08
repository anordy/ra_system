<html>
<table style="border-collapse:collapse;">
    <thead>
    <tr>
        <th style="text-align:center;" colspan="16" height="70">
            <strong>ZANZIBAR Revenue Authority</strong><br>
            <strong>{{ $title }}</strong><br>
            @if ($parameters['period'] == 'Annual')
                <strong>{{ $parameters['year'] }}</strong>
            @elseif ($parameters['period'] != null)
                <strong>From {{ $parameters['dates']['from'] }} To {{ $parameters['dates']['to'] }} </strong>
            @endif

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
            <strong>Start Date</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>End Date</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>No. of Installments</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Total Amount</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Amount Per Installment</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Paid Amount</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Outstanding Amount</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Currency</strong>
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
                {{ $record->installment_from->toDateString() }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->installment_to->toDateString() }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->installment_count }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ number_format($record->amount, 2) }} {{ $record->currency }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ number_format($record->amount / $record->installment_count, 2) }} {{ $record->currency }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ number_format($record->paidAmount(), 2) }} {{ $record->currency }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ number_format($record->amount - $record->paidAmount(), 2) }} {{ $record->currency }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->currency }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

</html>
