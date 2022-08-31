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
    </style>
</head>

<body style="font-size: 6pt">
    <table style="border-collapse:collapse; width:100%">
        <thead>
            <tr>
                <th style="text-align:center;" colspan="10">
                    <strong class="zrb">ZANZIBAR REVENUE BOARD</strong><br>
                    {{-- <strong>RELIEF APPLLICATIONS</strong><br>
                        <strong>From {{ $dates['from'] }} To {{ $dates['to'] }}</strong> --}}
                </th>
            </tr>
        </thead>
    </table>
    <br>
    <table class="table">
        <thead class="tableHead">
            <tr>
                <th style="text-align:center; border: 1px solid black;">
                    <strong>S/N</strong>
                </th>
                <th style="text-align:center; border: 1px solid black;">
                    <strong>Business</strong>
                </th>
                <th style="text-align:center; border: 1px solid black;">
                    <strong>Location</strong>
                </th>

                {{-- <th style="text-align:center; border: 1px solid black;">
                    <strong>Tax Type</strong>
                </th> --}}

                <th style="text-align:center; border: 1px solid black;">
                    <strong>Taxpayer</strong>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $index => $record)
                <tr>
                    <td style="text-align:center; border: 1px solid black;">
                        {{ $index + 1 }}
                    </td>
                    <td style="text-align:center; border: 1px solid black;">
                        {{ $record->business->name ?? '-' }}
                    </td>
                    <td style="text-align:center; border: 1px solid black;">
                        {{ $record->name }}
                    </td>
                    {{-- <td style="text-align:center; border: 1px solid black;">
                        {{ $record-> }}
                    </td> --}}
                    <td style="text-align:center; border: 1px solid black;">
                        {{ $record->taxpayer->full_name ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <br>
</body>

</html>
