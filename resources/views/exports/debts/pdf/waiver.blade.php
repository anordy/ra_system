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
    </style>
</head>

<body style="font-size: 8pt">

    <table style="border-collapse:collapse; width:100%">
        <thead>
            <tr>
                <th style="text-align:center;" colspan="15">
                    <strong class="zrb">ZANZIBAR REVENUE BOARD</strong><br>
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
                <th style="text-align:center; border-collapse:collapse;border: 1px solid black;">
                    <strong>S/N</strong>
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
                    <strong>Principal Amount</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Interest Amount</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Penalty Amount</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Interest %</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Penalty %</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Total Outstanding</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Status</strong>
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
                    {{ $record->debt->business->name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->debt->location->name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->debt->taxType->name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->debt->currency ?? '-' }}
                </td>
                @if ($record->debt_type === 'App\Models\Returns\TaxReturn')
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $record->debt->principal === null ? '-' : number_format($record->debt->principal, 2) }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $record->debt->interest === null ? '-' : number_format($record->debt->interest, 2) }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $record->debt->penalty === null ? '-' : number_format($record->debt->penalty, 2) }}
                    </td>
                @elseif($record->debt_type === 'App\Models\TaxAssessments\TaxAssessment')
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $record->debt->principal_amount === null ? '-' : number_format($record->debt->principal_amount, 2) }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $record->debt->interest_amount === null ? '-' : number_format($record->debt->interest_amount, 2) }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $record->debt->penalty_amount === null ? '-' : number_format($record->debt->penalty_amount, 2) }}
                    </td>
                @endif

                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->penalty_rate === null ? '-' : number_format($record->penalty_rate, 2) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->interest_rate === null ? '-' : number_format($record->interest_rate, 2) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->debt->curr_payment_due_date == null ? '-' : date('M, d Y', strtotime($record->debt->payment_due_date)) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->status }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>


</html>
