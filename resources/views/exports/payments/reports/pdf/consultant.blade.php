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
                    {{-- <strong>{{ $title }}</strong><br> --}}

                    @if ($parameters['dates']['startDate'] != null)
                    <strong>From {{ date("M, d Y", strtotime($parameters['dates']['from'])) }} To {{ date("M, d Y",
                        strtotime($parameters['dates']['to'])) }} </strong><br>
                    @endif
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
                    <strong>Full Name</strong>
                </th>
                <th class="text-center border">
                    <strong>Phone Number</strong>
                </th>
                <th class="text-center border">
                    <strong>Email</strong>
                </th>
                <th class="text-center border">
                    <strong>Amount</strong>
                </th>
                <th class="text-center border">
                    <strong>Currency</strong>
                </th>
                <th class="text-center border">
                    <strong>Description</strong>
                </th>
                <th class="text-center border">
                    <strong>Control Number</strong>
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
                    {{ $record->payer_name ?? '-' }}
                </td>
                <td class="text-center border">
                    {{ $record->payer_phone_number ?? '-' }}
                </td>
                <td class="text-center border">
                    {{ $record->payer_email ?? '-' }}
                </td>

                <td class="text-center border">
                    {{ $record->amount ?? '-' }}
                </td>
                <td class="text-center border">
                    {{ $record->currency ?? '-' }}
                </td>

                <td class="text-center border">
                    {{ $record->description ?? '-' }}
                </td>

                <td class="text-center border">
                    {{ $record->control_number ?? '-' }}
                </td>
                <td class="text-center border">
                    {{ $record->status ?? '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>


</html>