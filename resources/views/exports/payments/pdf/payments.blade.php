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
                {{-- <th style="text-align:center;" colspan="15">
                    <strong>ZANZIBAR REVENUE BOARD</strong><br>
                    <strong>Report of
                        {{ $parameters['type'] == 'Filing' ? $parameters['filing_report_type'] : $parameters['payment_report_type'] }}
                        for {{ $parameters['tax_type_name'] }} </strong><br>
                    @if ($parameters['dates']['startDate'] != null)
                        <strong>From {{ date('M, d Y', strtotime($parameters['dates']['from'])) }} To
                            {{ date('M, d Y', strtotime($parameters['dates']['to'])) }}
                        </strong><br>
                    @endif

                    <strong>Tax Regions :
                        @foreach ($parameters['tax_regions'] as $t => $id)
                            <span>{{ App\Models\TaxRegion::find($id)->name }}</span>
                            @if (count($parameters['tax_regions']) > 1)
                                @if ($t == end($parameters['tax_regions']) - 2)
                                    and
                                @else
                                    ,
                                @endif
                            @endif &nbsp;
                        @endforeach
                    </strong><br>

                    Location:
                    @if ($parameters['region'] == 'all')
                        <strong> Pemba and Unguja </strong><br>
                    @else
                        <strong>{{ App\Models\Region::find($parameters['region'])->name }}</strong><br>
                    @endif


                    @if ($parameters['district'] != 'all')
                        <strong>District: {{ App\Models\District::find($parameters['district'])->name }}</strong><br>
                    @endif


                    @if ($parameters['ward'] != 'all')
                        <strong> Ward: {{ App\Models\Ward::find($parameters['ward'])->name }}</strong><br>
                    @endif

                    <strong>Total Number of Records: {{ $records->count() }} </strong>
                </th> --}}
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
                    <strong>Control No.</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Bill Amount</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Currency </strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Payer Name</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Payer Name</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Payer Email</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Payer Phone Number</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Description</strong>
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
                        {{ $record->control_number ?? '-' }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ number_format($record->amount) ?? '-' }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $record->currency ?? '-' }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $record->payer_name ?? '-' }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $record->payer_email ?? '-' }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $record->payer_phone_number ?? '-' }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $record->description ?? '-' }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $record->status ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>


</html>
