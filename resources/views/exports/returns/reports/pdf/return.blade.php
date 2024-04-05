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
            table-layout: fixed;
        }

        table td {
            word-wrap: break-word;
            overflow-wrap: break-word;
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
                    <strong>Report of
                        {{ $parameters['type'] == 'Filing' ? $parameters['filing_report_type'] : $parameters['payment_report_type'] }}
                        for {{ $parameters['tax_type_name'] }} </strong><br>
                    <strong>From {{ date('M, d Y', strtotime($parameters['range_start'])) }} To
                        {{ date('M, d Y', strtotime($parameters['range_end'])) }} </strong><br>

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
                    <strong>Reporting Month</strong>
                </th>
                <th class="text-center border">
                    <strong>Filed By</strong>
                </th>
                <th class="text-center border">
                    <strong>Currency</strong>
                </th>
                <th class="text-center border">
                    <strong>Principal Amount</strong>
                </th>
                <th class="text-center border">
                    <strong>Interest Amount</strong>
                </th>
                <th class="text-center border">
                    <strong>Penalty Amount</strong>
                </th>
                <th class="text-center border">
                    <strong>Total Amount</strong>
                </th>
                <th class="text-center border">
                    <strong>Outstanding Amount</strong>
                </th>
                <th class="text-center border">
                    <strong>Filing Date</strong>
                </th>
                <th class="text-center border">
                    <strong>Filing Due Date</strong>
                </th>
                <th class="text-center border">
                    <strong>Payment Date</strong>
                </th>
                <th class="text-center border">
                    <strong>Payment Due Date</strong>
                </th>
                <th class="text-center border">
                    <strong>Filing Status</strong>
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
                        {{ $record->business->name ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->location->name ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->taxType->name ?? '-' }}
                    </td>
                    <td class="text-center border">
                        @if ($record->taxType->code == 'lumpsum-payment')
                            {{ \App\Models\Returns\LumpSum\LumpSumReturn::where('id', $record->return_id)->first()->quarter_name ?? '-' }}
                        @else
                            {{ $record->financialMonth->name ?? '-' }}
                        @endif
                    </td>
                    <td class="text-center border">
                        {{ $record->taxpayer->full_name ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->currency ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $record->principal === null ? '-' : number_format($record->principal, 2) }}
                    </td>
                    <td class="text-center border">
                        {{ $record->interest === null ? '-' : number_format($record->interest, 2) }}
                    </td>
                    <td class="text-center border">
                        {{ $record->penalty === null ? '-' : number_format($record->penalty, 2) }}
                    </td>
                    <td class="text-center border">
                        {{ $record->total_amount === null ? '-' : number_format($record->total_amount, 2) }}
                    </td>
                    <td class="text-center border">
                        {{ $record->outstanding_amount === null ? '-' : number_format($record->outstanding_amount, 2) }}
                    </td>
                    <td class="text-center border">
                        {{ date('d/m/Y', strtotime($record->created_at)) }}
                    </td>
                    <td class="text-center border">
                        {{ $record->filing_due_date == null ? '-' : date('d/m/Y', strtotime(\Carbon\Carbon::create($record->filing_due_date)->addMonth())) }}
                    </td>

                    <td class="text-center border">
                        {{ $record->paid_at == null ? '-' : date('d/m/Y', strtotime($record->paid_at)) }}
                    </td>
                    <td class="text-center border">
                        {{ $record->curr_payment_due_date == null ? '-' : date('d/m/Y', strtotime($record->curr_payment_due_date)) }}
                    </td>
                    <td class="text-center border">
                        @if ($record->created_at > \Carbon\Carbon::create($record->filing_due_date)->addMonth())
                            Late Filing
                        @else
                            In-Time Filing
                        @endif
                    </td>
                    <td class="text-center border">
                        @if (!$record->paid_at)
                            Not Paid
                        @elseif($record->paid_at > $record->curr_payment_due_date)
                            Late Payment
                        @elseif($record->paid_at <= $record->curr_payment_due_date)
                            Paid
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>


</html>
