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
                <strong>Reporting Month</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Filed By</strong>
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
            <th style="text-align:center; border-collapse:collapse;border: 1px solid black;">
                <strong>Filing Date</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Filing Due Date</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Payment Date</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Payment Due Date</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Filing Status</strong>
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
                    @if ($record->taxType->code == 'lumpsum-payment')
                        {{ \App\Models\Returns\LumpSum\LumpSumReturn::where('id',$record->return_id)->first()->quarter_name ?? '-'}}
                        @else
                        {{ $record->financialMonth->name ?? '-' }}
                    @endif
                    
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->taxpayer->full_name ?? '-' }}
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
                    {{$record->filing_due_date==null?'-': date('M, d Y', strtotime($record->filing_due_date)) }}
                </td>
                
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->paid_at==null?'-':date('M, d Y', strtotime($record->paid_at)) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->payment_due_date==null?'-':date('M, d Y', strtotime($record->payment_due_date)) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    @if ($record->created_at > $record->filing_due_date )
                        Late Filing
                        @else
                        In-Time Filing
                    @endif
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    @if($record->paid_at > $record->payment_due_date)
                            Late Payment
                        @elseif($record->paid_at < $record->payment_due_date)
                            In-Time Payment
                        @else
                            Not Paid
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</html>
