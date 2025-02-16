<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Letter of Motor Vehicle Temporary Transportation - {{ $transport->mvr->plate_number }}</title>
    <style>
        .align-center {
            text-align: center;
        }

        img.logo {
            height: 120px;
            margin-bottom: 20px;
        }

        .yellow-bottom {
            border-bottom: 4px solid #e7e149;
        }

        .blue-bottom {
            border-bottom: 4px solid #467fbc;
        }

        table {
            border-collapse: collapse;
        }

        tr.border-bottom td {
            border-bottom: 1px dotted black;
        }

        tr.border-top td {
            border-top: 1px dotted black;
        }

        @page {
            size: 7in 9.25in;
            margin: 10mm 15mm 10mm 15mm;
        }

        .bold {
            font-weight: bold;
        }

        p {
            font-size: 22px;
        }

        .td-content {
            width: 50%;
            font-size: 22px;
        }

        .table-bordered td {
            border: solid 1px rgba(0, 0, 0, 0.36);
        }

        td {
            padding: 5px;
        }

        .letterhead {
            height: 80px;
        }

        .mb-2 {
            margin-bottom: 6px;
        }
    </style>
</head>

<body style="font-size: 22px !important;">
<div class="align-center">
    <img class="logo" src="{{ public_path() }}/images/logo-square.png" alt="ZRA Logo">
</div>
<table width="100%" class="yellow-bottom">
    <tr>
        <td width="100%" style="font-size: 28px; text-transform: uppercase;text-align: center;font-family: sans-serif; letter-spacing: 14px" class="bold">Zanzibar Revenue Authority</td>
    </tr>
</table>
<table width="100%" class="blue-bottom" style="margin: 5px 0 20px 0">
</table>
<table width="100%" style="margin-bottom: 6px;">
    <tr>
        <td width="50%" style="font-size: 22px; padding: 0">Reference Number: <b>{{ $transport->referenceNumber }}</b></td>
    </tr>
</table>
<table width="100%" style="margin-bottom: 18px;">
    <tr>
        <td width="50%" style="font-size: 22px; padding: 0" class="">
            Issued Date: <b>{{ $transport->approved_on ? $transport->approved_on->toFormattedDayDateString() : 'N/A' }}</b>
        </td>
    </tr>
</table>
<div class="align-center mb-2">
    <span style="margin-top: 8px; font-weight: bold; font-size: 22px; display: block; padding: 0 70px">
        RUHUSA YA KUSAFIRISHA GARI KWA MUDA NAMBA {{ $transport->mvr->plate_number }} NA CHASIS NAMBA {{ $transport->mvr->chassis->chassis_number }} NJE YA ZANZIBAR
    </span>
</div>
<div>
    <p>
        Mamlaka ya Mapato Zanzibar, haina pingamizi na ombi lako la kusafirisha gari kwa muda kuanzia tarehe {{ $transport->date_of_travel->toFormattedDateString() }} hadi {{ $transport->extended_date->toFormattedDateString() ?? $transport->date_of_return->toFormattedDateString() }}
    </p>
    <p>
        Aidha, unatakiwa kumjuilisha Kamishna Mkuu mara tu gari hiyo itakaporejeshwa Zanzibar au venginevyo.
    </p>
    <p>
        Ahsante.
    </p>
    <table width="100%" style="margin-top: 20px">
        <tr>
            <td class="td-content align-center"><b>"Tulipe Kodi kwa Maendeleo ya Zanzibar"</b></td>
        </tr>
    </table>
    <div id="qr-code" width="100%" style="text-align: center; margin-top: 100px">
        <img src="{{ $dataUri }}" alt="qr-code"/>
    </div>
</div>
<div class="align-center" style="position:fixed; bottom: 0">
    <img class="letterhead" src="{{ public_path() . '/images/letterhead.png'  }}">
</div>
</body>
</html>
