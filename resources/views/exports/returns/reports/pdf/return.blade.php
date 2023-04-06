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
                    {{-- <strong>{{ $title }}</strong><br> --}}
                    <strong>Report of {{ $parameters['type'] == 'Filing' ? $parameters['filing_report_type'] :
                        $parameters['payment_report_type'] }} for {{ $parameters['tax_type_name'] }} </strong><br>
                    <strong>From {{ date("M, d Y", strtotime($parameters['range_start'])) }} To {{ date("M, d Y",
                        strtotime($parameters['range_end'])) }} </strong><br>

                    <strong>Tax Regions :
                        @foreach ($parameters['tax_regions'] as $t=>$id)
                        <span>{{ App\Models\TaxRegion::find($id)->name }}</span>
                        @if (count($parameters['tax_regions']) > 1)
                        @if($t == (end($parameters['tax_regions'] )-2))
                        and @else ,
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
                    <strong>Reporting Month</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Filed By</strong>
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
                    <strong>Total Amount</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Outstanding Amount</strong>
                </th>
                <th style="text-align:center; border-collapse:collapse;border: 1px solid black;">
                    <strong>Filing Date</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Filing Due Date</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Payment Date</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Payment Due Date</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Filing Status</strong>
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
                    {{ $record->business->name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->location->name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->taxType->name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    @if ($record->taxType->code == 'lumpsum-payment')
                    {{ \App\Models\Returns\LumpSum\LumpSumReturn::where('id',$record->return_id)->first()->quarter_name
                    ?? '-'}}
                    @else
                    {{ $record->financialMonth->name ?? '-' }}
                    @endif
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->taxpayer->full_name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->currency ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->principal===null?'-':number_format($record->principal, 2) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{$record->interest===null?'-':number_format($record->interest, 2) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{$record->penalty===null?'-':number_format($record->penalty, 2) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{$record->total_amount===null?'-':number_format($record->total_amount, 2) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{$record->outstanding_amount===null?'-':number_format($record->outstanding_amount, 2) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ date('d/m/Y', strtotime($record->created_at)) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{$record->filing_due_date==null?'-': date('d/m/Y', strtotime($record->filing_due_date)) }}
                </td>

                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->paid_at==null?'-':date('d/m/Y', strtotime($record->paid_at)) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $record->payment_due_date==null?'-':date('d/m/Y', strtotime($record->payment_due_date)) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    @if ($record->created_at > $record->filing_due_date )
                    Late Filing
                    @else
                    In-Time Filing
                    @endif
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    @if($record->paid_at > $record->payment_due_date)
                    Late Payment
                    @elseif($record->paid_at < $record->payment_due_date)
                        In-Time Payment
                        @else
                        Not Paid
                        @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>


</html>