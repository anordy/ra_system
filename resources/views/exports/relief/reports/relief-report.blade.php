<html>
{{-- <div class="card-header text-uppercase font-weight-bold bg-grey ">
        Relief Applications Count
    </div> --}}
<table style="border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:center;" colspan="10" height="70">
                <strong>ZANZIBAR REVENUE AUTHORITY</strong><br>
                <strong>RELIEF APPLICATIONS</strong><br>
                <strong>From {{ $dates['from'] }} To {{ $dates['to'] }}</strong>
            </th>
        </tr>
    </thead>
</table>
<br>
<table style="border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:center;" colspan="10">
                <strong>RELIEF APPLLICATIONS</strong>
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
            <th style="text-align:center; border-collapse:collapse;border: 1px solid black;">
                <strong>Project Name</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Project Description</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Project Section</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>VAT amount</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Relieved amount</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Rate</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Supplier Name</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Supplier Location</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Registered Date</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reliefs as $index => $relief)
            <tr>
                <td style="border-collapse:collapse;border: 1px solid black;">{{ $index + 1 }}</td>
                <td style="border-collapse:collapse;border: 1px solid black;">{{ $relief->project->name }}</td>
                <td style="border-collapse:collapse;border: 1px solid black;">{{ $relief->project->description }}</td>
                <td style="border-collapse:collapse;border: 1px solid black;">{{ $relief->projectSection->name }}</td>
                <td style="border-collapse:collapse;border: 1px solid black;">{{ number_format($relief->vat_amount, 1) }}
                </td>
                <td style="border-collapse:collapse;border: 1px solid black;">
                    {{ number_format($relief->relieved_amount, 1) }}</td>
                <td style="border-collapse:collapse;border: 1px solid black;">{{ $relief->rate }}%</td>
                <td style="border-collapse:collapse;border: 1px solid black;">{{ $relief->business->name }}</td>
                <td style="border-collapse:collapse;border: 1px solid black;">{{ $relief->location->name }}</td>
                <td style="border-collapse:collapse;border: 1px solid black;">{{ $relief->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<br><br>
<table style="border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:center;" colspan="{{ count($projectSectionsArray) + 2 }}">
                <strong>RELIEVED AMOUNT SUMMARY</strong>
            </th>
        </tr>
    </thead>
</table>
<table>
    <thead>
        <tr>
            <th style="text-align:center;border: 1px solid black;"></th>
            @foreach ($projectSectionsArray as $project)
                <th style="text-align:center;border: 1px solid black;"> <strong>{{ $project['name'] }}</strong></th>
            @endforeach
            <th style="background-color: gray;text-align:center;border: 1px solid black;"><strong>TOTAL</strong></th>
        </tr>
    </thead>
    @php
        $totalAllMonths = 0;
    @endphp
    <tbody>
        @foreach ($data as $month => $content)
            <tr class="text-center">
                <td style="text-align:center;border: 1px solid black;">
                    {{ $month }}
                </td>
                @php
                    $sumAmountMonth[$month] = 0;
                @endphp
                @foreach ($content as $key => $projectSection)
                    @php
                        $sumProjectSection[$key] = $sumProjectSection[$key] ?? 0;
                    @endphp
                    <td style="text-align:center;border: 1px solid black;">
                        {{ $projectSection['relievedAmount'] == 0 ? '-' : number_format($projectSection['relievedAmount'],1) }}
                    </td>
                    <!--append the sum -->
                    @php
                        $sumAmountMonth[$month] += $projectSection['relievedAmount'];
                        $sumProjectSection[$key] += $projectSection['relievedAmount'];
                        $totalAllMonths = $totalAllMonths + $projectSection['relievedAmount'];
                    @endphp
                @endforeach
                <td style="background-color: gray;text-align:center;border: 1px solid black;">
                    {{ $sumAmountMonth[$month] == 0 ? '-' : number_format($sumAmountMonth[$month],1) }}
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center">
            <td style="background-color: gray;text-align:center;border: 1px solid black;">
                TOTAL
            </td>
            @foreach ($sumProjectSection as $key => $value)
                <td style="background-color: gray;text-align:center;border: 1px solid black;">
                    {{ $value == 0 ? '-' : number_format($value,1) }}
                </td>
            @endforeach
            <td style="background-color: gray;text-align:center;border: 1px solid black;">
                {{ $totalAllMonths == 0 ? '-' : number_format($totalAllMonths,1) }}
            </td>
        </tr>
    </tfoot>
</table>
<br><br>
<table style="border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:center;" colspan="{{ count($projectSectionsArray) + 2 }}">
                <strong>RELIEF COUNTS SUMMARY</strong>
            </th>
        </tr>
    </thead>
</table>
<table>
    <thead>
        <tr>
            <th style="text-align:center;border: 1px solid black;"></th>
            @foreach ($projectSectionsArray as $project)
                <th style="text-align:center;border: 1px solid black;"> <strong>{{ $project['name'] }}</strong></th>
            @endforeach
            <th style="background-color: gray;text-align:center;border: 1px solid black;"><strong>TOTAL</strong></th>
        </tr>
    </thead>
    @php
        $totalAmountMonths = 0;
    @endphp
    <tbody>
        @foreach ($data as $month => $content)
            <tr class="text-center">
                <td style="text-align:center;border: 1px solid black;">
                    {{ $month }}
                </td>
                @php
                    $sumMonth[$month] = 0;
                @endphp
                @foreach ($content as $key => $projectSection)
                    @php
                        $sumAmountProjectSection[$key] = $sumAmountProjectSection[$key] ?? 0;
                    @endphp
                    <td style="text-align:center;border: 1px solid black;">
                        {{ $projectSection['count'] == 0 ? '-' : number_format($projectSection['count'],1) }}
                    </td>
                    <!--append the sum -->
                    @php
                        $sumMonth[$month] += $projectSection['count'];
                        $sumAmountProjectSection[$key] += $projectSection['count'];
                        $totalAmountMonths = $totalAmountMonths + $projectSection['count'];
                    @endphp
                @endforeach
                <td style="background-color: gray;text-align:center;border: 1px solid black;">
                    {{ $sumMonth[$month] == 0 ? '-' : number_format($sumMonth[$month],1) }}
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center">
            <td style="background-color: gray;text-align:center;border: 1px solid black;">
                TOTAL
            </td>
            @foreach ($sumAmountProjectSection as $key => $value)
                <td style="background-color: gray;text-align:center;border: 1px solid black;">
                    {{ $value == 0 ? '-' : number_format($value,1) }}
                </td>
            @endforeach
            <td style="background-color: gray;text-align:center;border: 1px solid black;">
                {{ $totalAmountMonths == 0 ? '-' : number_format($totalAmountMonths,1) }}
            </td>
        </tr>
    </tfoot>
</table>

</html>
