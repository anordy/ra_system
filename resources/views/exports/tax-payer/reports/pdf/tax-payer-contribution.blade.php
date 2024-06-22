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
            color: rgb(19, 19, 19);
            font-weight: bold;
            font-size: 30px;
        }

        .title {
            text-transform: uppercase;
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

        .text-end{
            text-align: right;
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
            <strong class="zrb">ZANZIBAR REVENUE AUTHORITY</strong><br>
            <strong class="title">{{ $tittle }}</strong><br>
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
            <strong>BUSINESS NAME</strong>
        </th>
        <th class="text-center border">
            <strong>BUSINESS LOCATION</strong>
        </th>
        <th class="text-center border">
            <strong>ZIN</strong>
        </th>
        <th class="text-center border">
            <strong>TAX REGION</strong>
        </th>
        <th class="text-center border">
            <strong>CREATED AT</strong>
        </th>
        <th class="text-center border">
            <strong>TOTAL AMOUNT</strong>
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
                {{ $record->business_name ?? '-' }}
            </td>
            <td class="text-center border">
                {{ $record->business_location ?? '-' }}
            </td>
            <td class="text-center border">
                {{ $record->zin ?? '-'}}
            </td>
            <td class="text-center border">
                {{ $record->tax_region_name ?? '-'}}
            </td>
            <td class="text-center border">
                {{ date('Y-m-d', strtotime($record->created_at)) ?? '-' }}
            </td>
            <td class="text-end border">
                {{ number_format($record->total_amount, 2, '.',',') ?? '-'}}
            </td>

        </tr>
    @endforeach
    </tbody>
</table>
</body>


</html>
