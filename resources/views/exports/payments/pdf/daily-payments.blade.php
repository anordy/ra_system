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

        .text-center {
            text-align: center
        }

        .column{
            border-collapse:collapse;
            border: 1px solid black;
        }

        .table {
            width: 100%;
            background: transparent;
            border: 1px solid black;
            border-collapse: collapse;
        }

        .tableHead {
            background-color: rgb(182, 193, 208);
            color: rgb(0, 0, 0);

        }

        .p-10 {
            padding-bottom: 10px;
            padding-top: 10px;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>


<body style="font-size: 9pt">
    <table style="width:100%;">
        <thead>
            <tr>
                <th style="text-align:center;" colspan="3">
                    <strong>ZANZIBAR REVENUE BOARD</strong><br>
                        @if ($vars['range_start'] == date('Y-m-d'))
                        Collections on <span> {{ date('d-M-Y') }} </span>
                        @else
                        Collections From
                        <span> {{ date('d-M-Y',strtotime($vars['range_start'])) }} </span>
                        to
                        <span> {{ date('d-M-Y',strtotime($vars['range_end'])) }} </span>
                        @endif
                </th>
            </tr>
        </thead>
    </table>
    <br>

    <table class="table">
        <thead class="tableHead">
            <tr>
                <th class="text-left column">Source</th>
                <th class="text-right column">Shilings</th>
                <th class="text-right column">Dollars</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($taxTypes as $row)
            <tr>
                <td class="text-left column">{{ $row->name }}</td>
                <td class="text-right column">{{ number_format($row->getTotalPaymentsPerCurrency('TZS',$vars['range_start'],$vars['range_end']),2)
                    }}</td>
                <td class="text-right column">{{ number_format($row->getTotalPaymentsPerCurrency('USD',$vars['range_start'],$vars['range_end']),2)
                    }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th class="text-left column">Total</th>
                <th class="text-right column">{{ number_format($vars['tzsTotalCollection'],2) }}</th>
                <th class="text-right column">{{ number_format($vars['usdTotalCollection'],2) }}</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>