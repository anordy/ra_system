

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
            
        </style>
    </head>
    <body>
        <table style="border-collapse:collapse; width:100%">
            <thead>
                <tr>
                    <th style="text-align:center;" colspan="4" height="50">
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
                    <th style="text-align:center; border-collapse:collapse;border: 1px solid black;">
                        <strong>S/N</strong>
                    </th>
                    <th style="text-align:center; border-collapse:collapse;border: 1px solid black;">
                        <strong>BENEFICIARIES INSTITUTIONS</strong>
                    </th>
                    <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        <strong>DONORS</strong>
                    </th>
                    <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
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
                        <td style="border-collapse:collapse;border-left: 1px solid black;"></td>
                        <td ><strong> {{ $projectSection['name'] }}</strong></td>
                        <td ></td>
                        <td style="border-collapse:collapse;border-right: 1px solid black;"></td>
                    </tr>
                    @foreach ($projectSection['projects'] as $index => $project)
                        @php
                            $mainIndex++;
                        @endphp
                        <tr>
                            <td style="border-collapse:collapse;border: 1px solid black;">{{ $mainIndex }}</td>
                            <td style="border-collapse:collapse;border: 1px solid black;">{{ $project['name'] }}</td>
                            <td style="text-align:center; border-collapse:collapse;border: 1px solid black;">{{ $project['sponsor'] }}</td>
                            <td style="text-align:right; border-collapse:collapse;border: 1px solid black;">{{ number_format($project['relievedAmount'],1) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="border-collapse:collapse;border: 1px solid black;"></td>
                        <td style="border-collapse:collapse;border-bottom: 1px solid black;"> <strong>SUB TOTAL</strong></td>
                        <td style="border-collapse:collapse;border-bottom: 1px solid black;"></td>
                        <td style="text-align:right; border-collapse:collapse;border: 1px solid black;"> <strong>{{ number_format($projectSection['subTotal'],1) }}</strong></td>
                    </tr>
                    @php
                        $total += $projectSection['subTotal'];
                    @endphp
                @endforeach
                <tr>
                    <td style="border-collapse:collapse;border-bottom: 1px solid black;"></td>
                    <td style="border-collapse:collapse;border-bottom: 1px solid black;"> <strong>GRAND TOTAL</strong></td>
                    <td style="border-collapse:collapse;border-bottom: 1px solid black;"></td>
                    <td style="text-align:right; border-collapse:collapse;border: 1px solid black;"> <strong> {{ number_format($total,1) }} </strong></td>
                </tr>
            </tbody>
        </table>
        <br><br>
    </body>


</html>
