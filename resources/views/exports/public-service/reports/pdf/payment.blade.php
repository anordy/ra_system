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
            background: transparent;
            table-layout: fixed;
        }

        table td {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .border {
            border-collapse: collapse;
            border: 1px solid black;
        }

        .text-center {
            text-align: center;
        }

        .font-size-8 {
            font-size: 8pt;
        }

        .top-table {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
</head>

<body class="font-size-8">

    <table class="top-table">
        <thead>
            <tr>
                <th class="text-center" colspan="15">
                    <strong>ZANZIBAR Revenue Authority</strong><br>
                    <strong>Report of {{ $parameters['payment_type']  }} Public Service Payments </strong><br>
                    @if($parameters['range_start'] && $parameters['range_end'])
                        <strong>From {{ date("M, d Y", strtotime($parameters['range_start'])) }} To {{ date("M, d Y",
                    strtotime($parameters['range_end'])) }} </strong><br>
                    @endif


                    <strong>Total Number of Records: {{ $records->count() }} </strong>
                </th>
            </tr>
        </thead>
    </table>
    <br>
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
                    <strong>Plate Number</strong>
                </th>
                <th class="text-center border">
                    <strong>Registration Type</strong>
                </th>
                <th class="text-center border">
                    <strong>Currency</strong>
                </th>
                <th class="text-center border">
                    <strong>Amount</strong>
                </th>
                <th class="text-center border">
                    <strong>Start Date</strong>
                </th>
                <th class="text-center border">
                    <strong>End Date</strong>
                </th>
                <th class="text-center border">
                    <strong>Payment Status</strong>
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
                        {{ $record->motor->mvr->plate_number ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->motor->mvr->regtype->name ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->currency ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->amount ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ date('M, d Y', strtotime($record->start_date)) }}
                    </td>
                    <td class="text-center border">
                        {{ $record->end_date==null?'-':date('M, d Y', strtotime($record->end_date)) }}
                    </td>
                    <td class="text-center border">
                        @if (!$record->paid_at)
                            Not Paid
                        @else
                            Paid
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>


</html>
