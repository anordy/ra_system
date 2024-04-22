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
            <strong class="zrb">ZANZIBAR REVENUE AUTHORITY</strong><br>
            <strong>{{ $title }}</strong><br>
            {{-- <strong>From {{ $dates['from'] }} To {{ $dates['to'] }}</strong> --}}
            @if ($parameters['period'] == 'Annual')
                <strong>{{ $parameters['year'] }}</strong>
            @elseif ($parameters['period'] != null)
                <strong>From {{ $parameters['dates']['from'] }} To {{ $parameters['dates']['to'] }} </strong>
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
            <strong>Business</strong>
        </th>
        <th class="text-center border">
            <strong>Location</strong>
        </th>
        <th class="text-center border">
            <strong>Tax Type</strong>
        </th>
        <th class="text-center border">
            <strong>Start Date</strong>
        </th>
        <th class="text-center border">
            <strong>End Date</strong>
        </th>
        <th class="text-center border">
            <strong>No. of Installments</strong>
        </th>
        <th class="text-center border">
            <strong>Total Amount</strong>
        </th>
        <th class="text-center border">
            <strong>Amount Per Installment</strong>
        </th>
        <th class="text-center border">
            <strong>Paid Amount</strong>
        </th>
        <th class="text-center border">
            <strong>Outstanding Amount</strong>
        </th>
        <th class="text-center border">
            <strong>Currency</strong>
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
                    {{ $record->location->name ?? '-' }}
                </td>
                <td class="text-center border">
                    {{ $record->taxType->name ?? '-' }}
                </td>
                <td class="text-center border">
                    {{ $record->installment_from->toDateString() }}
                </td>
                <td class="text-center border">
                    {{ $record->installment_to->toDateString() }}
                </td>
                <td class="text-center border">
                    {{ $record->installment_count }}
                </td>
                <td class="text-center border">
                    {{ number_format($record->amount, 2) }} {{ $record->currency }}
                </td>
                <td class="text-center border">
                    {{ number_format($record->amount / $record->installment_count, 2) }} {{ $record->currency }}
                </td>
                <td class="text-center border">
                    {{ number_format($record->paidAmount(), 2) }} {{ $record->currency }}
                </td>
                <td class="text-center border">
                    {{ number_format($record->amount - $record->paidAmount(), 2) }} {{ $record->currency }}
                </td>
                <td class="text-center border">
                    {{ $record->currency }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>


</html>
