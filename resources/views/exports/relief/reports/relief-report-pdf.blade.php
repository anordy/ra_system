<html>

<head>
    <title></title>
    <style>
        body {
            background-image: url("{{ public_path() }}/images/logo.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            margin: 15px;
            opacity: 0.1;
        }
        thead {
            text-align: center
        }

        .tableHead {
            background-color: rgb(182, 193, 208);
            color: rgb(0, 0, 0);
        
        }

        tbody tr:nth-child(odd) {
            background-color: #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #ddd;
        }

        .total{
            background-color: rgb(201, 201, 201);
            color: rgb(0, 0, 0);
            font-weight: bold;
        }

        .zrb{
            /* background-color: rgb(182, 193, 208); */
            color: rgb(19, 19, 19);
            font-weight: bold;
            font-size: 30px;
        }
        .table{
            border: 1px solid black;
            width: 100%;
            border-collapse: collapse;
        }
        
    </style>
</head>

<body style="font-size: 6pt">
  
        <table style="border-collapse:collapse; width:100%">
            <thead>
                <tr>
                    <th style="text-align:center;" colspan="10" >
                        <strong class="zrb">ZANZIBAR REVENUE BOARD</strong><br>
                        <strong>RELIEF APPLLICATIONS</strong><br>
                        <strong>From {{ $dates['from'] }} To {{ $dates['to'] }}</strong>
                    </th>
                </tr>
            </thead>
        </table>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th colspan="10">
                        <strong>RELIEF APPLLICATIONS</strong>
                    </th>
                </tr>
            </thead>
        </table>
        <table class="table">
            <thead class="tableHead">
                <tr>
                    <th style="text-align:center; border: 1px solid black;">
                        <strong>S/N</strong>
                    </th>
                    <th style="text-align:center; border: 1px solid black;">
                        <strong>Project Name</strong>
                    </th>
                    <th style="text-align:center;border: 1px solid black;">
                        <strong>Project Description</strong>
                    </th>
                    <th style="text-align:center; border: 1px solid black;">
                        <strong>Project Section</strong>
                    </th>
                    <th style="text-align:center; border: 1px solid black;">
                        <strong>VAT amount</strong>
                    </th>
                    <th style="text-align:center; border: 1px solid black;">
                        <strong>Relieved amount</strong>
                    </th>
                    <th style="text-align:center; border: 1px solid black;">
                        <strong>Rate</strong>
                    </th>
                    <th style="text-align:center; border: 1px solid black;">
                        <strong>Supplier Name</strong>
                    </th>
                    <th style="text-align:center; border: 1px solid black;">
                        <strong>Supplier Location</strong>
                    </th>
                    <th style="text-align:center; border: 1px solid black;">
                        <strong>Registered Date</strong>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reliefs as $index => $relief)
                    <tr>
                        <td style="border: 1px solid black;">{{ $index + 1 }}</td>
                        <td style=" border: 1px solid black;">{{ $relief->project->name }}</td>
                        <td style=" border: 1px solid black;">
                            {{ $relief->project->description }}</td>
                        <td style=" border: 1px solid black;">
                            {{ $relief->projectSection->name }}</td>
                        <td style=" border: 1px solid black;">
                            {{ number_format($relief->vat_amount, 1) }}
                        </td>
                        <td style=" border: 1px solid black;">
                            {{ number_format($relief->relieved_amount, 1) }}</td>
                        <td style=" border: 1px solid black;">{{ number_format($relief->rate,1) }}%</td>
                        <td style=" border: 1px solid black;">{{ $relief->business->name }}
                        </td>
                        <td style=" border: 1px solid black;">{{ $relief->location->name }}
                        </td>
                        <td style=" border: 1px solid black;">{{ $relief->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br><br>
        <table class="table">
            <thead>
                <tr>
                    <th style="text-align:center;" colspan="{{ count($projectSectionsArray) + 2 }}">
                        <strong>RELIEVED AMOUNT SUMMARY</strong>
                    </th>
                </tr>
            </thead>
        </table>
        <table class="table">
            <thead class="tableHead">
                <tr>
                    <th style="text-align:center;border: 1px solid black;"></th>
                    @foreach ($projectSectionsArray as $project)
                        <th style="text-align:center;border: 1px solid black;"> <strong>{{ $project['name'] }}</strong>
                        </th>
                    @endforeach
                    <th class="total" style="text-align:center;border: 1px solid black;"><strong>TOTAL</strong>
                    </th>
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
                                {{ $projectSection['relievedAmount'] == 0 ? '-' : number_format($projectSection['relievedAmount'], 1) }}
                            </td>
                            <!--append the sum -->
                            @php
                                $sumAmountMonth[$month] += $projectSection['relievedAmount'];
                                $sumProjectSection[$key] += $projectSection['relievedAmount'];
                                $totalAllMonths = $totalAllMonths + $projectSection['relievedAmount'];
                            @endphp
                        @endforeach
                        <td class="total" style="text-align:center;border: 1px solid black;">
                            {{ $sumAmountMonth[$month] == 0 ? '-' : number_format($sumAmountMonth[$month], 1) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-center">
                    <td class="total" style="text-align:center;border: 1px solid black;">
                        TOTAL
                    </td>
                    @foreach ($sumProjectSection as $key => $value)
                        <td class="total" style="text-align:center;border: 1px solid black;">
                            {{ $value == 0 ? '-' : number_format($value, 1) }}
                        </td>
                    @endforeach
                    <td class="total" style="text-align:center;border: 1px solid black;">
                        {{ $totalAllMonths == 0 ? '-' : number_format($totalAllMonths, 1) }}
                    </td>
                </tr>
            </tfoot>
        </table>
        <br><br>
        <table class="table">
            <thead>
                <tr>
                    <th style="text-align:center;" colspan="{{ count($projectSectionsArray) + 2 }}">
                        <strong>RELIEF COUNTS SUMMARY</strong>
                    </th>
                </tr>
            </thead>
        </table>
        <table class="table" >
            <thead class="tableHead">
                <tr>
                    <th style="text-align:center;border: 1px solid black;"></th>
                    @foreach ($projectSectionsArray as $project)
                        <th style="text-align:center;border: 1px solid black;"> <strong>{{ $project['name'] }}</strong>
                        </th>
                    @endforeach
                    <th class="total" style="text-align:center;border: 1px solid black;"><strong>TOTAL</strong>
                    </th>
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
                                {{ $projectSection['count'] == 0 ? '-' : number_format($projectSection['count']) }}
                            </td>
                            <!--append the sum -->
                            @php
                                $sumMonth[$month] += $projectSection['count'];
                                $sumAmountProjectSection[$key] += $projectSection['count'];
                                $totalAmountMonths = $totalAmountMonths + $projectSection['count'];
                            @endphp
                        @endforeach
                        <td class="total" style="text-align:center;border: 1px solid black;">
                            {{ $sumMonth[$month] == 0 ? '-' : number_format($sumMonth[$month]) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-center">
                    <td class="total" style="text-align:center;border: 1px solid black;">
                        TOTAL
                    </td>
                    @foreach ($sumAmountProjectSection as $key => $value)
                        <td class="total" style="text-align:center;border: 1px solid black;">
                            {{ $value == 0 ? '-' : number_format($value) }}
                        </td>
                    @endforeach
                    <td class="total" style="text-align:center;border: 1px solid black;">
                        {{ $totalAmountMonths == 0 ? '-' : number_format($totalAmountMonths) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    
</body>

</html>
