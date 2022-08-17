<html>
<table style="border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:center;" colspan="17" height="70">
                <strong>ZANZIBAR REVENUE AUTHORITY</strong><br>
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
                <strong>Financial Month</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Financial Year</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Filed By</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Currency</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>VAT Amount Due (TZS)</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>VAT Amount Due (USD)</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Amount Due With Penalties (TZS)</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Amount Due With Penalties (USD)</strong>
            </th>
            <th style="text-align:center; border-collapse:collapse;border: 1px solid black;">
                <strong>Filing Date</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Filing Due Date</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Filing Status</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Payment Date</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Payment Due Date</strong>
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
                    {{ $record->branch->name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->financialMonth->name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->financialYear->name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->taxpayer->full_name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->currency ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->total_vat_payable_tzs===null?'-':number_format($record->total_vat_payable_tzs, 2) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->total_vat_payable_usd===null?'-':number_format($record->total_vat_payable_usd, 2) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->total_amount_due_with_penalties_tzs===null?'-':number_format($record->total_amount_due_with_penalties_tzs, 2) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->total_amount_due_with_penalties_usd===null?'-':number_format($record->total_amount_due_with_penalties_usd, 2) }}
                </td>
                {{-- <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{$record->total_amount_due_with_penalties===null?'-':number_format($record->total_amount_due_with_penalties, 2) }}
                </td> --}}
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ date('d/m/Y', strtotime($record->created_at)) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{$record->filing_due_date==null?'-': date('d/m/Y', strtotime($record->filing_due_date)) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    @if ($record->created_at == null || $record->filing_due_date == null)
                        -
                    @else
                        @if ($record->created_at < $record->filing_due_date)
                            In-Time
                        @else
                            Late
                        @endif
                    @endif
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->paid_at==null?'-':date('d/m/Y', strtotime($record->paid_at)) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->payment_due_date==null?'-':date('d/m/Y', strtotime($record->payment_due_date)) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">     
                    @if ($record->created_at == null || $record->payment_due_date == null)
                        -
                    @else
                        @if ($record->created_at < $record->payment_due_date)
                            In-Time
                        @else
                            Late
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</html>
