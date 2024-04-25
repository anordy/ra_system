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
    </style>
</head>

<body style="font-size: 8pt">

<table style="border-collapse:collapse; width:100%">
    <thead>
    <tr>
        <th style="text-align:center;" colspan="15">
            <strong>ZANZIBAR Revenue Authority</strong><br>
            <strong>Report of PBZ Statements</strong><br>
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
        <th style="text-align:center; border-collapse:collapse;border: 1px solid black;">
            <strong>S/N</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Account No.</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Account Name</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Currency </strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Statement Date</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Generated At</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>No. of Transactions</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Opening Balance</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Closing Balance</strong>
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach ($records as $index => $record)
        <tr>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $index + 1 }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->account_no ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->account_name ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->currency ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->stmdt ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->credttm ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->nboftxs ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ number_format($record->openbal, 2) ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ number_format($record->closebal, 2) ?? '-' }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>


</html>
