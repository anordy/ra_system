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
            <strong class="zrb">ZANZIBAR REVENUE AUTHORITY</strong><br>
            <strong>{{ $title }}</strong><br>
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
            <strong>Tax Payer</strong>
        </th>
        <th class="text-center border">
            <strong>Business</strong>
        </th>
        <th class="text-center border">
            <strong>Location</strong>
        </th>
        <th class="text-center border">
            <strong>Tax Type</strong>
        </th>
        <th class="text-center border">
            <strong>Currency</strong>
        </th>
        <th class="text-center border">
            <strong>Amount</strong>
        </th>
        <th class="text-center border">
            <strong>Financial Month</strong>
        </th>
        <th class="text-center border">
            <strong>Financial Year</strong>
        </th>
        <th class="text-center border">
            <strong>Created At</strong>
        </th>
        <th class="text-center border">
            <strong>Claim Status</strong>
        </th>
        <th class="text-center border">
            <strong>Approved On</strong>
        </th>
        <th class="text-center border">
            <strong>Payment Method</strong>
        </th>
        <th class="text-center border">
            <strong>Installments Count</strong>
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
                {{ $record->business->taxpayer->first_name.' '.$record->business->taxpayer->middle_name.' '.$record->business->taxpayer->last_name ?? '-' }}
            </td>
            <td class="text-center border">
                {{ $record->business->name ?? '-' }}
            </td>
            <td class="text-center border">
                {{ $record->location->name ?? '-' }}
            </td>
            <td class="text-center border">
                {{ $record->taxType->name ?? '-' }}
            </td>
            <td class="text-center border">
                {{ $record->currency ?? '-' }}
            </td>
            <td class="text-center border">
                {{ $record->amount===null?'-':number_format($record->amount, 2) }}
            </td>
            <td class="text-center border">
                {{ $record->financialMonth->name ?? '-'}}
            </td>
            <td class="text-center border">
                {{ $record->financialMonth->year->code ?? '-'}}
            </td>
            <td class="text-center border">
                {{ $record->created_at ?? '-' }}
            </td>
            <td class="text-center border">
                {{ $record->status ?? '-' }}
            </td>
            <td class="text-center border">
                {{ $record->approved_on ?? '-' }}
            </td>
            <td class="text-center border">
                {{ $record->payment_method ?? '-' }}
            </td>
            <td class="text-center border">
                {{ $record->installments_count ?? '-' }}
            </td>
            <td class="text-center border">
                {{ $record->payment_status ?? '-' }}
            </td>

        </tr>
    @endforeach
    </tbody>
</table>
</body>


</html>
