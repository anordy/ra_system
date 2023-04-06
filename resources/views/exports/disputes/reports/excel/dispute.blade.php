<html>

<table style="border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:center;" colspan="16" height="70">
                <strong>ZANZIBAR Revenue Authority</strong><br>
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
                    <strong>Bussiness Name</strong>
                </th>
               
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Category</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Tax in Dispute</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Tax not in Dispute</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Tax Deposit</strong>
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
                        {{ $record->category ?? '-' }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ number_format($record->tax_in_dispute, 2) }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ number_format($record->tax_not_in_dispute, 2) }}
                    </td>

                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ number_format($record->tax_deposit, 2) }}
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</html>
