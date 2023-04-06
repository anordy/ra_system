<html>
<table style="border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:center; border-collapse:collapse;border: 1px solid black;" colspan="3" height="70px">
                <strong>ZANZIBAR Revenue Authority</strong><br>
                <strong>
                    @if ($vars['range_start'] == date('Y-m-d'))
                    Collections on <span> {{ date('d-M-Y') }} </span>
                    @else
                    Collections From <span> {{ date('d-M-Y',strtotime($vars['range_start'])) }} </span> to <span> {{ date('d-M-Y',strtotime($vars['range_end'])) }} </span>
                    @endif
                </strong><br>
                <strong>Total Number of Records: {{ $taxTypes->count() }} </strong>
            </th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="border-collapse:collapse;border: 1px solid black;"><strong>Source</strong></th>
            <th style="border-collapse:collapse;border: 1px solid black;text-align:right"><strong>TZS</strong></th>
            <th style="border-collapse:collapse;border: 1px solid black;text-align:right"><strong>USD</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($taxTypes as $row)
        <tr>
            <td style="border-collapse:collapse;border: 1px solid black;">{{ $row->name }}</td>
            <td style="border-collapse:collapse;border: 1px solid black;text-align:right">{{
                number_format($row->getTotalPaymentsPerCurrency('TZS',$vars['range_start'],$vars['range_end']),2)
                }}</td>
            <td style="border-collapse:collapse;border: 1px solid black;text-align:right">{{
                number_format($row->getTotalPaymentsPerCurrency('USD',$vars['range_start'],$vars['range_end']),2)
                }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th style="border-collapse:collapse;border: 1px solid black;"><strong>Total</strong></th>
            <th style="border-collapse:collapse;border: 1px solid black;text-align:right"><strong>{{ number_format($vars['tzsTotalCollection'],2) }}</strong></th>
            <th style="border-collapse:collapse;border: 1px solid black;text-align:right"><strong>{{ number_format($vars['usdTotalCollection'],2) }}</strong></th>
        </tr>
    </tfoot>
</table>

</html>