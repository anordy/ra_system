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
            border-bottom-color: black;
        }
        .text-left{
            text-align: left;
        }
    </style>
</head>

<body style="font-size: 8pt">
    <table style="border-collapse:collapse; width:100%">
        <thead>
            <tr>
                <th style="text-align:center;" colspan="8">
                    <strong>ZANZIBAR REVENUE BOARD</strong><br>
                </th>
            </tr>
        </thead>
    </table>
    <br>

    <table class="table">
        <thead class="border-bottom border-dark">
            <tr>
                <td colspan="8">{{ now()->firstOfMonth()->format('d/m/Y') }} to {{ now()->format('d/m/Y') }}</th>
            </tr>
            <tr>
                <th colspan="8" class="p-10"><strong>Provisional Daily Receipts</strong></th>
            </tr>
            <tr class="bg-gray p-10">
                <th colspan="4" class="text-center">Today's Collections</th>
                <th colspan="4" class="text-center">Collection to Date</th>
            </tr>
            <tr style="padding-top:20px; padding-bottom:20px">
                <td class="text-left">Source</td>
                <td class="text-left">Shilings</td>
                <td class="text-left">Dollar</td>
                <td class="text-left"></td>
                <td class="text-left"></td>
                <td class="text-left">Shilings</td>
                <td class="text-left">Dollar</td>
                <td class="text-left"></td>
            </tr>
        </thead>
        <tbody class="border-bottom">
            {{-- <tr>
                <th>Unguja</th>
            </tr> --}}
            @foreach ($vars['taxTypes'] as $row)
                <tr>
                    <td class="text-left">{{ $row->name }}</td>
                    <td class="text-left">{{ number_format($row->tzsDailyPayments,2) }}</td>
                    <td class="text-left">{{ number_format($row->usdDailyPayments,2) }}</td>
                    <td class="text-left"></td>
                    <td class="text-left"></td>
                    <td class="text-left">{{ number_format($row->tzsMonthlyPayments,2) }}</td>
                    <td class="text-left">{{ number_format($row->usdMonthlyPayments,2) }}</td>
                    <td class="text-left"></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th class="text-left"></th>
                <th class="text-left">{{ number_format($vars['todayTzsTotalCollection'],2) }}</th>
                <th class="text-left">{{ number_format($vars['todayUsdTotalCollection'],2) }}</th>
                <th class="text-left"></th>
                <th class="text-left"></th>
                <th class="text-left">{{ number_format($vars['monthTzsTotalCollection'],2) }}</th>
                <th class="text-left">{{ number_format($vars['monthUsdTotalCollection'],2) }}</th>
                <th class="text-left"></th>
            </tr>
        </tfoot>
    </table>
</body>


</html>
