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
                    <strong>Report of {{ strtoupper($status) }} PAYMENTS</strong><br>
                    <strong>From {{ date("M, d Y", strtotime($parameters['range_start'])) }} To {{ date("M, d Y",
                        strtotime($parameters['range_end'])) }} </strong><br>
                    <strong>Total Records: {{ count($records) }}</strong>
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
                    <strong>Control No.</strong>
                </th>
                <th class="text-center border">
                    <strong>Bill Amount</strong>
                </th>
                @if ($status == 'paid')
                    <th class="text-center border">
                        <strong>Paid Amount</strong>
                    </th>
                    <th class="text-center border">
                        <strong>PSP Name</strong>
                    </th>
                @endif
                <th class="text-center border">
                    <strong>Currency </strong>
                </th>
                <th class="text-center border">
                    <strong>Business Name</strong>
                </th>
                <th class="text-center border">
                    <strong>Payer Name</strong>
                </th>
                <th class="text-center border">
                    <strong>Payer Phone Number</strong>
                </th>
                <th class="text-center border">
                    <strong>Description</strong>
                </th>
                <th class="text-center border">
                    <strong>Status</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>PBZ Status</strong>
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
                        {{ $record->control_number ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ number_format($record->amount,2) ?? '-' }}
                    </td>
                    @if ($status == 'paid')
                        <td class="text-center border">
                            {{ number_format($record->paid_amount,2) ?? '-' }}
                        </td>
                        <td class="text-center border">
                            {{ $record->payment->psp_name ?? '-' }}
                        </td>
                    @endif
                    <td class="text-center border">
                        {{ $record->currency ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->billable->business->name ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->payer_name ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->payer_phone_number ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->description ?? '-' }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ strtoupper($record->status ?? 'N/A') }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ strtoupper($record->pbz_status ?? 'N/A') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>


</html>
