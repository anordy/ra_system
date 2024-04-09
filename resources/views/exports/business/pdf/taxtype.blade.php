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
        .tax {
            /* background-color: rgb(182, 193, 208); */
            padding-top: 50px%;
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

        .total {
            background-color: rgb(201, 201, 201);
            color: rgb(0, 0, 0);
            font-weight: bold;
        }

        .zrb {
            /* background-color: rgb(182, 193, 208); */
            color: rgb(19, 19, 19);
            font-weight: bold;
            font-size: 30px;
        }

        .table {
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

        .text-left {
            text-align: left;
        }

        .font-size-6 {
            font-size: 6pt;
        }

        .top-table {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
</head>

<body class="font-size-6">
    <table class="top-table">
        <thead>
            <tr>
                <th class="text-center" colspan="10">
                    <strong class="zrb">ZANZIBAR REVENUE AUTHORITY</strong><br>
                    <strong>Business Tax Type Registration Report on </strong>
                    @if(array_key_exists('tax_type_name',$parameters))
                      <strong> {{$parameters['tax_type_name']}}  </strong>
                    @endif
                    @if ($parameters['year'] === 'all')
                        <strong>overall period of time</strong>
                    @elseif ($parameters['year'] === 'range')
                        <strong>From {{ $parameters['range_start'] }} To {{ $parameters['range_end'] }} </strong>
                    @else
                        <strong>{{ $parameters['year'] }}</strong>
                    @endif

                    {{-- <strong>RELIEF APPLLICATIONS</strong><br> --}}
                    {{-- <strong>From {{ $dates['from'] }} To {{ $dates['to'] }}</strong>  --}}
                </th>
            </tr>
        </thead>
    </table>
    <br>
    @foreach($recordsData as $group => $records)
    <table class="top-table">
        <thead>
            <tr>
                <th class="text-left" colspan="10">
                    <strong class="tax" > {{ $records[0]->taxtype->name }}</strong><br>
                   
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
                    <strong>Business</strong>
                </th>
                <th class="text-center border">
                    <strong>Location</strong>
                </th>
                <th class="text-center border">
                    <strong>Tax Region</strong>
                </th>
                <th class="text-center border">
                    <strong>Business Category</strong>
                </th>

                <th class="text-center border">
                    <strong>Taxpayer</strong>
                </th>
             
                <th class="text-center border">
                    <strong>Effective Date</strong>
                </th>
                <th class="text-center border">
                    
                    <strong>Region</strong>
                </th>

                <th class="text-center border">
                    <strong>District</strong>
                </th>
                <th class="text-center border">
                    <strong>Ward</strong>
                </th>
                <th class="text-center border">
                    <strong>Physical Address</strong>
                </th>
                <th class="text-center border">
                    <strong>Status</strong>
                </th>
            </tr>
        </thead>
        <tbody>
           
            @foreach ($records as $index => $record) 
                <tr>
                    <td class="text-center border">
                        {{ $index + 1 }}
                    </td>
                    <td class="text-center border">
                        {{ $record->business->name ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->name }}
                    </td>
                    <td class="text-center border">
                        {{ $record->taxRegion->name ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->business->category->name ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->taxpayer->fullname ?? '-' }}
                    </td>
                 
                    <td class="text-center border">
                        {{ date('M, d Y', strtotime($record->effective_date)) ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->region->name ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->district->name ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->ward->name ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->physical_address ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ ucfirst($record->business->status ?? '') ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach


    <br>
</body>

</html>
