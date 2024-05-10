

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
                background-color: #fff;
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

            .top-table {
                border-collapse: collapse;
                width: 100%;
            }

            .text-right {
                text-align:right;
            }

            .border-collapse {
                border-collapse: collapse;
            }

            .border-left {
                border-collapse:collapse;
                border-left: 1px solid black;
            }

            .border-right {
                border-collapse:collapse;
                border: 1px solid black;
            }
            
        </style>
    </head>
    <body>
        <table class="top-table">
            <thead>
                <tr>
                    <th class="text-center" colspan="4" height="50">
                        <strong>ZANZIBAR REVENUE AUTHORITY</strong><br>
                        <strong>RELIEFS CEILING REPORT</strong><br>
                        <strong>From {{ $dates['from'] }} To {{ $dates['to'] }}</strong>
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
                        <strong>BENEFICIARIES INSTITUTIONS</strong>
                    </th>
                    <th class="text-center border">
                        <strong>DONORS</strong>
                    </th>
                    <th class="text-center border">
                        <strong>VAT SPECIAL RELIEF (Tsh)</strong>
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $mainIndex = 0;
                    $total = 0;
                @endphp
                @foreach ($projectSections as $projectSection)
                    <tr>
                        <td class="border-left"></td>
                        <td ><strong> {{ $projectSection['name'] }}</strong></td>
                        <td ></td>
                        <td class="border-right"></td>
                    </tr>
                    @foreach ($projectSection['projects'] as $index => $project)
                        @php
                            $mainIndex++;
                        @endphp
                        <tr>
                            <td class="border">{{ $mainIndex }}</td>
                            <td class="border">{{ $project['name'] }}</td>
                            <td class="text-center border">{{ $project['sponsor'] }}</td>
                            <td class="text-right border">{{ number_format($project['relievedAmount'],1) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="border"></td>
                        <td class="border"> <strong>SUB TOTAL</strong></td>
                        <td class="border"></td>
                        <td class="text-right border"> <strong>{{ number_format($projectSection['subTotal'],1) }}</strong></td>
                    </tr>
                    @php
                        $total += $projectSection['subTotal'];
                    @endphp
                @endforeach
                <tr>
                    <td class="border"></td>
                    <td class="border"> <strong>GRAND TOTAL</strong></td>
                    <td class="border"></td>
                    <td class="text-right border"> <strong> {{ number_format($total,1) }} </strong></td>
                </tr>
            </tbody>
        </table>
        <br><br>
    </body>


</html>
