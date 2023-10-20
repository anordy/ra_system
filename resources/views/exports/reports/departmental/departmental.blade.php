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
                    Collections From <span> {{ date('d-M-Y',strtotime($vars['range_start'])) }} </span> to
                    <span> {{ date('d-M-Y',strtotime($vars['range_end'])) }} </span>
                @endif
            </strong>
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
    @if ($nonRevenueTaxTypes->isNotEmpty() && !($vars['departmentType'] == 'domestic-taxes' && $vars['location'] == \App\Models\Region::UNGUJA))
        <tr class="text-center">
            <th colspan="3" style="font-weight: bold; text-align: center;">Non Tax Revenue Department</th>
        </tr>
        @foreach ($nonRevenueTaxTypes as $row)
            <tr>
                <td class="text-left">{{ $row->name }}</td>
                <td>{{ isset($report['TZS'][$row->id]) ? number_format($report['TZS'][$row->id],2) : '0' }}</td>
                <td>{{ isset($report['USD'][$row->id]) ? number_format($report['USD'][$row->id],2) : '0' }}</td>
            </tr>
        @endforeach
    @endif

    @if ($domesticTaxTypes->isNotEmpty()  && !($vars['departmentType'] == 'non-tax-revenue' && $vars['location'] == \App\Models\Region::UNGUJA))
        <tr class="text-center">
            <th colspan="3" style="font-weight: bold; text-align: center;">Domestic Taxes Department</th>
        </tr>
        @foreach ($domesticTaxTypes as $row)
            <tr>
                <td class="text-left">{{ $row->name }}</td>
                <td>{{ isset($report['TZS'][$row->id]) ? number_format($report['TZS'][$row->id], 2) : '0' }}</td>
                <td>{{ isset($report['USD'][$row->id]) ? number_format($report['USD'][$row->id], 2) : '0' }}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
    <tfoot>
    <tr>
        <th style="border-collapse:collapse;border: 1px solid black;"><strong>Total</strong></th>
        <th style="border-collapse:collapse;border: 1px solid black;text-align:right">
            <strong>{{ number_format(array_sum($report['TZS'] ?? [0]), 2) }}</strong></th>
        <th style="border-collapse:collapse;border: 1px solid black;text-align:right">
            <strong>{{ number_format(array_sum($report['USD'] ?? [0]), 2) }}</strong></th>
    </tr>
    </tfoot>
</table>

</html>