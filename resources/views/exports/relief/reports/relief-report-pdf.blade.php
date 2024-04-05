<html>

<head>
    <title></title>
    <style>
        body {
            background-image: url("{{ public_path() }}/images/logo.png");
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

        .border {
            border-collapse: collapse;
            border: 1px solid black;
        }

        .text-center {
            text-align: center;
        }

        .font-size-6 {
            font-size: 6pt;
        }

        .top-table {
            border-collapse: collapse;
            width: 100%;
        }
        
        .plain-border {
            border: 1px solid black;
        }
        
    </style>
</head>

<body class="font-size-6">
  
        <table class="top-table">
            <thead>
                <tr>
                    <th class="text-center" colspan="10" >
                        <strong class="zrb">ZANZIBAR REVENUE AUTHORITY</strong><br>
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
                    <th class="text-center border">
                        <strong>S/N</strong>
                    </th>
                    <th class="text-center border">
                        <strong>Project Name</strong>
                    </th>
                    <th class="text-center border">
                        <strong>Project Description</strong>
                    </th>
                    <th class="text-center border">
                        <strong>Project Section</strong>
                    </th>
                    <th class="text-center border">
                        <strong>VAT amount</strong>
                    </th>
                    <th class="text-center border">
                        <strong>Relieved amount</strong>
                    </th>
                    <th class="text-center border">
                        <strong>Rate</strong>
                    </th>
                    <th class="text-center border">
                        <strong>Supplier Name</strong>
                    </th>
                    <th class="text-center border">
                        <strong>Supplier Location</strong>
                    </th>
                    <th class="text-center border">
                        <strong>Registered Date</strong>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reliefs as $index => $relief)
                    <tr>
                        <td class="plain-border">{{ $index + 1 }}</td>
                        <td class="plain-border">{{ $relief->project->name }}</td>
                        <td class="plain-border">
                            {{ $relief->project->description }}</td>
                        <td class="plain-border">
                            {{ $relief->projectSection->name }}</td>
                        <td class="plain-border">
                            {{ number_format($relief->vat_amount, 1) }}
                        </td>
                        <td class="plain-border">
                            {{ number_format($relief->relieved_amount, 1) }}</td>
                        <td class="plain-border">{{ number_format($relief->rate,1) }}%</td>
                        <td class="plain-border">{{ $relief->business->name }}
                        </td>
                        <td class="plain-border">{{ $relief->location->name }}
                        </td>
                        <td class="plain-border">{{ $relief->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br><br>
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center" colspan="{{ count($projectSectionsArray) + 2 }}">
                        <strong>RELIEVED AMOUNT SUMMARY</strong>
                    </th>
                </tr>
            </thead>
        </table>
        <table class="table">
            <thead class="tableHead">
                <tr>
                    <th class="text-center border"></th>
                    @foreach ($projectSectionsArray as $project)
                        <th class="text-center border"> <strong>{{ $project['name'] }}</strong>
                        </th>
                    @endforeach
                    <th class="text-center border total"><strong>TOTAL</strong>
                    </th>
                </tr>
            </thead>
            @php
                $totalAllMonths = 0;
            @endphp
            <tbody>
                @foreach ($data as $month => $content)
                    <tr class="text-center">
                        <td class="text-center border">
                            {{ $month }}
                        </td>
                        @php
                            $sumAmountMonth[$month] = 0;
                        @endphp
                        @foreach ($content as $key => $projectSection)
                            @php
                                $sumProjectSection[$key] = $sumProjectSection[$key] ?? 0;
                            @endphp
                            <td class="text-center border">
                                {{ $projectSection['relievedAmount'] == 0 ? '-' : number_format($projectSection['relievedAmount'], 1) }}
                            </td>
                            <!--append the sum -->
                            @php
                                $sumAmountMonth[$month] += $projectSection['relievedAmount'];
                                $sumProjectSection[$key] += $projectSection['relievedAmount'];
                                $totalAllMonths = $totalAllMonths + $projectSection['relievedAmount'];
                            @endphp
                        @endforeach
                        <td class="text-center border total">
                            {{ $sumAmountMonth[$month] == 0 ? '-' : number_format($sumAmountMonth[$month], 1) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-center">
                    <td class="text-center border total">
                        TOTAL
                    </td>
                    @foreach ($sumProjectSection as $key => $value)
                        <td class="text-center border total">
                            {{ $value == 0 ? '-' : number_format($value, 1) }}
                        </td>
                    @endforeach
                    <td class="text-center border total">
                        {{ $totalAllMonths == 0 ? '-' : number_format($totalAllMonths, 1) }}
                    </td>
                </tr>
            </tfoot>
        </table>
        <br><br>
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center" colspan="{{ count($projectSectionsArray) + 2 }}">
                        <strong>RELIEF COUNTS SUMMARY</strong>
                    </th>
                </tr>
            </thead>
        </table>
        <table class="table" >
            <thead class="tableHead">
                <tr>
                    <th class="text-center border"></th>
                    @foreach ($projectSectionsArray as $project)
                        <th class="text-center border"> <strong>{{ $project['name'] }}</strong>
                        </th>
                    @endforeach
                    <th class="text-center border total"><strong>TOTAL</strong>
                    </th>
                </tr>
            </thead>
            @php
                $totalAmountMonths = 0;
            @endphp
            <tbody>
                @foreach ($data as $month => $content)
                    <tr class="text-center">
                        <td class="text-center border">
                            {{ $month }}
                        </td>
                        @php
                            $sumMonth[$month] = 0;
                        @endphp
                        @foreach ($content as $key => $projectSection)
                            @php
                                $sumAmountProjectSection[$key] = $sumAmountProjectSection[$key] ?? 0;
                            @endphp
                            <td class="text-center border">
                                {{ $projectSection['count'] == 0 ? '-' : number_format($projectSection['count']) }}
                            </td>
                            <!--append the sum -->
                            @php
                                $sumMonth[$month] += $projectSection['count'];
                                $sumAmountProjectSection[$key] += $projectSection['count'];
                                $totalAmountMonths = $totalAmountMonths + $projectSection['count'];
                            @endphp
                        @endforeach
                        <td class="text-center border total">
                            {{ $sumMonth[$month] == 0 ? '-' : number_format($sumMonth[$month]) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-center">
                    <td class="text-center border total">
                        TOTAL
                    </td>
                    @foreach ($sumAmountProjectSection as $key => $value)
                        <td class="text-center border total">
                            {{ $value == 0 ? '-' : number_format($value) }}
                        </td>
                    @endforeach
                    <td class="text-center border total">
                        {{ $totalAmountMonths == 0 ? '-' : number_format($totalAmountMonths) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    
</body>

</html>
