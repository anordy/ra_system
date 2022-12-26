<html>
<head>
    <title></title>
    <style>
        body {
            background-image: url("{{ public_path() }}/images/logo.png");
            background-repeat: no-repeat;
            background-position: center;
            background-size:contain;
            margin: 15px;
            opacity: 0.03;
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
            margin-bottom: 3px;
        }

        .table {
            border: 1px solid black;
            width: 100%;
            border-collapse: collapse;
            background: transparent;
        }

        .tbl-bordered td, .tbl-bordered th, .tbl-bordered {
            border: .5px solid black;
            border-collapse: collapse;
        }

        .tbl-p-6 td, .tbl-p-6 th {
            padding: 6px;
        }
    </style>
</head>
<body style="font-size: 8pt">
<table style="border-collapse:collapse; width:100%">
    <thead>
    <tr>
        <th style="text-align:center;" colspan="15">
            <p class="zrb">ZANZIBAR REVENUE BOARD</p>
            <strong>{{ $return->taxType->name }} Tax Return</strong><br>
        </th>
    </tr>
    </thead>
</table>
<br>
<table style="width: 100%;" class="tbl-bordered tbl-p-6">
    <tbody>
    <tr>
        <td>
            <strong>Business Name</strong>
        </td>
        <td>{{ $return->business->name }}</td>
    </tr>
    <tr>
        <td>
            <strong>Business Location</strong>
        </td>
        <td>{{ $return->businessLocation->name }}</td>
    </tr>
    <tr>
        <td>
            <strong>Return Month</strong>
        </td>
        <td>{{ $return->quarter_name }}</td>
    </tr>
    <tr>
        <td>
            <strong>Filed By</strong>
        </td>
        <td>{{ $return->taxpayer->fullName }}</td>
    </tr>
    <tr>
        <td>
            <strong>Return Category</strong>
        </td>
        <td>{{ strtoupper($return->return_category) }}</td>
    </tr>
    <tr>
        <td>
            <strong>Application Status</strong>
        </td>
        <td>{{ strtoupper($return->application_status) }}</td>
    </tr>
    </tbody>
</table>
@includeIf('print.returns.includes.lumpsum')
<table style="border-collapse:collapse; width:100%">
    <thead>
    <tr>
        <th>
            <p style="margin-bottom: 0px; margin-top: 25px; text-align: left; font-size: 18px; text-transform: uppercase">Penalties</p>
        </th>
    </tr>
    </thead>
</table>
<table class="tbl-bordered tbl-p-6" style="width: 100%; margin-top: 10px;">
    <thead>
    <tr>
        <th>Month</th>
        <th>Tax Amount</th>
        <th>Late Filing Amount</th>
        <th>Late Payment Amount</th>
        <th>Interest Rate</th>
        <th>Interest Amount</th>
        <th>Penalty Amount</th>
    </tr>
    </thead>

    <tbody>
    @if(count($return->penalties))
        @foreach ($return->penalties as $penalty)
            <tr>
                <td>{{ $penalty['financial_month_name'] }}</td>
                <td>{{ number_format($penalty['tax_amount'], 2) }} <strong>{{ $return->currency}}</strong></td>
                <td>{{ number_format($penalty['late_filing'], 2) }} <strong>{{ $return->currency}}</strong></td>
                <td>{{ number_format($penalty['late_payment'], 2) }} <strong>{{ $return->currency}}</strong></td>
                <td>{{ number_format($penalty['rate_percentage'], 2) }} <strong>%</strong></td>
                <td>{{ number_format($penalty['rate_amount'], 2) }} <strong>{{ $return->currency}}</strong></td>
                <td>{{ number_format($penalty['penalty_amount'], 2)}} <strong>{{ $return->currency}}</strong></td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="7" class="text-center py-3">
                No penalties for this return.
            </td>
        </tr>
    @endif
    </tbody>
</table>
<table style="border-collapse:collapse; width:100%">
    <thead>
    <tr>
        <th>
            <p style="margin-bottom: 0px; margin-top: 25px; text-align: left; font-size: 18px; text-transform: uppercase;">Bill Summary</p>
        </th>
    </tr>
    </thead>
</table>
@php($bill = $return->tax_return->latestBill)
@if($bill)
    <table class="tbl-bordered tbl-p-6" style="width: 100%; margin-top: 10px;">
        <tbody>
        <tr>
            <th width="20%">Bill Description</th>
            <td colspan="2">{{ $bill->description }}</td>
        </tr>
        @foreach ($bill->bill_items as $item)
            <tr>
                <th width="20%">Bill Item</th>
                <td>{{ $item->taxType->name }}</td>
                <th>{{ number_format($item->amount, 2) }}</th>
            </tr>
        @endforeach
        <tr class="bg-secondary">
            <th colspan="2">Total Billed Amount</th>
            <th class="text-right">{{ number_format($bill->amount, 2) }} {{ $bill->currency }}</th>
        </tr>
        </tbody>
    </table>
@else
    <table class="tbl-bordered tbl-p-6" style="width: 100%; margin-top: 10px;">
        <thead>
        <tr>
            <td class="text-center">
                Return has no bill.
            </td>
        </tr>
        </thead>
    </table>
@endif
</body>
</html>
