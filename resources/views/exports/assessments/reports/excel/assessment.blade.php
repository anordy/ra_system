<html>

<table style="border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:center;" colspan="16" height="70">
                <strong>ZANZIBAR REVENUE BOARD</strong><br>
                <strong>{{ $title }}</strong><br>
                {{-- <strong>From {{ $dates['from'] }} To {{ $dates['to'] }}</strong> --}}
                @if ($parameters['period'] == 'Annual')
                    <strong>{{ $parameters['year'] }}</strong>
                @elseif ($parameters['period'] != null)
                    <strong>From {{ $parameters['dates']['from'] }} To {{ $parameters['dates']['to'] }} </strong>
                @endif

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
                <strong>Location</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Tax Type</strong>
            </th>

            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Currency</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>All Debt</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Principal Amount</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Penalty and Interest</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Outstanding Amount</strong>
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
                    {{ $record->business->owner_designation ?? '-' }}
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
                    {{ number_format($record->principal_amount + $record->penalty_amount + $record->interest_amount, 2) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ number_format($record->principal_amount, 2) }}
                </td>

                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ number_format($record->penalty_amount + $record->interest_amount, 2) }}
                </td>

                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ number_format($record->outstanding_amount, 2) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>





</html>
