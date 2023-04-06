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
    </style>
</head>

<body style="font-size: 8pt">

<table style="border-collapse:collapse; width:100%">
    <thead>
    <tr>
        <th style="text-align:center;" colspan="15">
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
        <th style="text-align:center; border-collapse:collapse;border: 1px solid black;">
            <strong>S/N</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Tax Payer</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Business</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Location</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Tax Type</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Currency</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Amount</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Financial Month</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Financial Year</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Created At</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Claim Status</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Approved On</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Payment Method</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Installments Count</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Payment Status</strong>
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
                {{ $record->business->taxpayer->first_name.' '.$record->business->taxpayer->middle_name.' '.$record->business->taxpayer->last_name ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->business->name ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->location->name ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->taxType->name ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->currency ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->amount===null?'-':number_format($record->amount, 2) }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->financialMonth->name ?? '-'}}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->financialMonth->year->code ?? '-'}}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->created_at ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->status ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->approved_on ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->payment_method ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->installments_count ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->payment_status ?? '-' }}
            </td>

        </tr>
    @endforeach
    </tbody>
</table>
</body>


</html>
