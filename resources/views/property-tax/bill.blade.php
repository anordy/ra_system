<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Property Tax Bill</title>
    <style>
        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        .top img {
            width: 50px;
            height: 50px;
        }

        .top {
            border-bottom: 1px dashed black;
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

        .block {
            display: block;
        }

        .margin-top-bottom {
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .margin-top {
            margin-top: 8px;
        }

        .margin-bottom {
            margin-bottom: 8px;
        }

        .padding-left {
            padding-left: 10px;
        }

        .td-title {
            width: 30%;
        }

        .td-content {
            width: 70%;
        }

        .border_bottom {
            border-bottom: 1px solid black;
        }

        .border_top {
            border-top: 1px solid black;
        }

        td {
            border: solid 1px rgba(0, 0, 0, 0.36);
        }

    </style>
</head>

<body style="font-size: 12px !important;">
<div class="top align-center">
    <img src="{{ public_path() }}/images/logo.png" alt="ZRA Logo">
    <span style="margin-top: 8px; font-weight: bold; font-size: 18px; display: block">Zanzibar Revenue Authority
        </span>
</div>
<div class="top align-center">
  <span style="margin-top: 8px; font-weight: bold; font-size: 15px; display: block">P.O. BOX 2072
        </span>
    <span style="margin-top: 8px; font-weight: bold; font-size: 15px; display: block">Tel: 255242230639, Fax: 255242233904
        </span>
    <span style="margin-top: 8px; font-weight: bold; font-size: 15px; display: block">Email: zra@zanrevenue.org
        </span>
    <span style="margin-top: 8px; font-weight: bold; font-size: 15px; display: block">Property Tax Bill
        </span>
</div>
<br>
<div class="center">
    <table width="100%">
        <tr>
            <td colspan="2" class="padding-left td-title bold">Taxpayer's Name/Jina la Mlipakodi</td>
            <td colspan="2" class="td-content"><b>{{ $propertyPayment->property->taxpayer->fullname() }}</b></td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Taxpayer's Number/Namba ya Mlipakodi</td>
            <td colspan="2" class="td-content"> {{ $propertyPayment->property->taxpayer->reference_no }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Address/Anwani</td>
            <td colspan="2" class="td-content"> {{ $propertyPayment->property->taxpayer->physical_address }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Bill No</td>
            <td colspan="2" class="td-content"> {{ $propertyPayment->id }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Date issued/Tarehe</td>
            <td colspan="2" class="td-content">
                {{ \Carbon\Carbon::create($propertyPayment->created_at)->format('d M Y H:i:s') }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Payment Control No/Nambari ya Malipo</td>
            <td colspan="2" class="td-content"> {{ $propertyPayment->latestBill->control_number }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold"></td>
            <td colspan="2" class="td-content"></td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Being Payment of Annual Property Tax for the Property
                located
                at
            </td>
            <td colspan="2" class="td-content"> {{ $propertyPayment->property->street->name }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Tax Rate/Kiwango</td>
            <td colspan="2" class="td-content"> {{ number_format($propertyPayment->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Property Tax Arrears/Malimbikizo ya Kodi</td>
            <td colspan="2" class="td-content"> {{ number_format($propertyPayment->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Total/Jumla</td>
            <td colspan="2" class="td-content"> {{ number_format($propertyPayment->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">NOTE:</td>
            <td colspan="2" class="padding-left td-content"></td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title">All payments (in Tanzania Shillings) should be addressed to
                the Commissioner General Zanzibar Revenue Authority.
            </td>
            <td colspan="2" class="padding-left td-content"></td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left">Failure to make the payment will attract an interest as prescribed
                under Section 33 of the Tax Administration and Procedures Act No. 7 of 2009.
            </td>
            <td colspan="2" class="padding-left"></td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title">- Please make sure the payment is done under the name
                of {{ $propertyPayment->property->taxpayer->fullname() }}</td>
            <td colspan="2" class="padding-left"></td>
        </tr>
    </table>

    <br>
</div>
<div class="bottom align-center">
    <p> Zanzibar Revenue Authority &copy; {{ date('Y') }} All Rights Reserved (ZRA)</p>
</div>
</body>

</html>
