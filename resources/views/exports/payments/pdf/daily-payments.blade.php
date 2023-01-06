<html>

<head>
    <title></title>
    <style>
        body {
            background-image: url("{{ public_path() }}/images/logo.png");
            background-repeat: no-repeat;
            background-position: center;
            /* background-size: contain; */
            margin: 15px;
            opacity: 0.1;
        }

        .text-center {
            text-align: center
        }


        .bg-gray{
            background-color: rgb(186, 186, 186);
        }

        .table {
            width: 100%;
            background: transparent;
        }

        .p-10{
            padding-bottom: 10px;
            padding-top: 10px;
        }

        .border-bottom{
            border-bottom: 1px solid black;
        }
        .border-dark{
            color: black;
        }
        .text-left{
            text-align: left;
        }
        .text-right{
            text-align: right;
        }
    </style>
</head>

<body style="font-size: 8pt">
    <table style="width:100%">
        <thead>
            <tr>
                <th style="text-align:center;" colspan="8">
                    <strong>ZANZIBAR REVENUE BOARD</strong><br>
                </th>
            </tr>
        </thead>
    </table>

    <table class="table">
        <thead class="border-bottom border-dark">
            <tr class="text-center">
                <td colspan="8">{{ now()->firstOfMonth()->format('d/m/Y') }} to {{ now()->format('d/m/Y') }}</th>
            </tr>
            <tr>
                <th colspan="8" class="p-10"><strong>Provisional Daily Receipts</strong></th>
            </tr>
            <tr class="bg-gray p-10">
                <th></th>
                <th colspan="2" class="text-center">Today's Collections</th>
                <th colspan="2" class=""></th>
                <th colspan="2" class="text-center">Collection to Date</th>
                <th></th>
            </tr>
            <tr style="padding-top:20px; padding-bottom:20px">
                <td class="text-left">Source</td>
                <td class="text-right">Shilings</td>
                <td class="text-right">Dollars</td>
                <td class=""></td>
                <td class=""></td>
                <td class="text-right">Shilings</td>
                <td class="text-right">Dollars</td>
                <td class="text-left"></td>
            </tr>
        </thead>
        <tbody class="border-bottom">
            @foreach ($vars['taxTypes'] as $row)
                <tr>
                    <td class="text-left">{{ $row->name }}</td>
                    <td class="text-right">{{ number_format($row->tzsDailyPayments,2) }}</td>
                    <td class="text-right">{{ number_format($row->usdDailyPayments,2) }}</td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right">{{ number_format($row->tzsMonthlyPayments,2) }}</td>
                    <td class="text-right">{{ number_format($row->usdMonthlyPayments,2) }}</td>
                    <td class="text-right"></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th class="text-left"></th>
                <th class="text-right">{{ number_format($vars['todayTzsTotalCollection'],2) }}</th>
                <th class="text-right">{{ number_format($vars['todayUsdTotalCollection'],2) }}</th>
                <th class="text-left"></th>
                <th class="text-left"></th>
                <th class="text-right">{{ number_format($vars['monthTzsTotalCollection'],2) }}</th>
                <th class="text-right">{{ number_format($vars['monthUsdTotalCollection'],2) }}</th>
                <th class="text-left"></th>
            </tr>
        </tfoot>
    </table>
</body>


</html>
